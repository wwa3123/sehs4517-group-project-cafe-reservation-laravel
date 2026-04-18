<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Member;
use App\Models\Reservation;
use App\Models\ReservedSlot;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EventService
{
    public function __construct(private ReservationService $reservationService) {}

    /**
     * Fetch all events with ticket counts and available slots.
     */
    public function listEvents(): LengthAwarePaginator
    {
        $paginator = Event::withSum([
            'registrations as registered_tickets' => fn ($q) => $q->where('payment_status', '!=', 'CANCELLED'),
        ], 'num_tickets')
            ->orderBy('event_date')
            ->paginate(15);

        $paginator->getCollection()->transform(function (Event $event) {
            $registered = (int) ($event->registered_tickets ?? 0);
            $event->registered_tickets = $registered;
            $event->available_tickets  = max(0, (int) $event->max_participants - $registered);
            return $event;
        });

        return $paginator;
    }

    /**
     * Return the IDs of events the given member has joined (non-cancelled).
     */
    public function joinedEventIds(int $memberId): Collection
    {
        return EventRegistration::where('member_id', $memberId)
            ->where('payment_status', '!=', 'CANCELLED')
            ->pluck('event_id')
            ->flip();
    }

    /**
     * Create an event and reserve the required table/time-slot combinations.
     */
    public function createEvent(array $data): Event
    {
        return DB::transaction(function () use ($data) {
            $event = Event::create([
                'event_name'          => $data['event_name'],
                'event_descriptions'  => $data['event_descriptions'] ?? null,
                'event_fee'           => $data['event_fee'],
                'max_participants'    => $data['max_participants'],
                'event_date'          => $data['event_date'],
            ]);

            $systemMember = Member::create([
                'email'            => 'event-' . $event->event_id . '@system.local',
                'first_name'       => $event->event_name,
                'last_name'        => '(Event)',
                'password_hash'    => Hash::make(bin2hex(random_bytes(16))),
                'role'             => 'system',
                'subscribe_events' => false,
                'loyalty_points'   => 0,
            ]);

            $eventDate = Carbon::parse($event->event_date)->toDateString();

            foreach ((array) $data['table_id'] as $tableId) {
                $this->reservationService->createReservation([
                    'member_id'     => $systemMember->member_id,
                    'event_id'      => $event->event_id,
                    'date'          => $eventDate,
                    'num_guests'    => $data['num_guests'],
                    'table_id'      => $tableId,
                    'time_slots_id' => $data['time_slots_id'],
                ]);
            }

            return $event;
        });
    }

    /**
     * Check whether a time-slot is already booked for a given table and date.
     */
    public function isSlotBooked(int $tableId, int $timeSlotId, string $date): bool
    {
        return ReservedSlot::where('table_id', $tableId)
            ->where('time_slots_id', $timeSlotId)
            ->whereHas('reservation', fn ($q) => $q->whereDate('date', $date))
            ->exists();
    }

    /**
     * Return ticket stats for an event.
     *
     * @return array{registered: int, available: int}
     */
    public function ticketStats(Event $event): array
    {
        $registered = (int) $event->registrations
            ->where('payment_status', '!=', 'CANCELLED')
            ->sum('num_tickets');

        return [
            'registered' => $registered,
            'available'  => max(0, (int) $event->max_participants - $registered),
        ];
    }

    /**
     * Register a member for an event. Returns true on success or an error key/message pair on failure.
     *
     * @return true|array{field: string, message: string}
     */
    public function joinEvent(Event $event, int $memberId, int $numTickets): true|array
    {
        $alreadyJoined = EventRegistration::where('event_id', $event->event_id)
            ->where('member_id', $memberId)
            ->where('payment_status', '!=', 'CANCELLED')
            ->exists();

        if ($alreadyJoined) {
            return ['field' => 'member_id', 'message' => 'This member has already joined this event.'];
        }

        return DB::transaction(function () use ($event, $memberId, $numTickets) {
            $registered = (int) EventRegistration::where('event_id', $event->event_id)
                ->where('payment_status', '!=', 'CANCELLED')
                ->sum('num_tickets');

            $available = (int) $event->max_participants - $registered;

            if ($numTickets > $available) {
                return ['field' => 'num_tickets', 'message' => 'Not enough available tickets for this event.'];
            }

            EventRegistration::create([
                'event_id'       => $event->event_id,
                'member_id'      => $memberId,
                'num_tickets'    => $numTickets,
                'payment_status' => 'PENDING',
            ]);

            return true;
        });
    }

    /**
     * Return the current table IDs, time-slot IDs, and num_guests for the event's system reservation,
     * used to pre-populate the edit form.
     *
     * @return array{currentTableIds: array, currentTimeSlotIds: array, currentNumGuests: int}
     */
    public function getEditData(Event $event): array
    {
        $systemMember       = Member::where('email', 'event-' . $event->event_id . '@system.local')->first();
        $currentTableIds    = [];
        $currentTimeSlotIds = [];
        $currentNumGuests   = (int) $event->max_participants;

        if ($systemMember) {
            $reservations       = Reservation::where('member_id', $systemMember->member_id)->with('reservedSlots')->get();
            $currentTableIds    = $reservations->flatMap->reservedSlots->pluck('table_id')->unique()->values()->toArray();
            $firstReservation   = $reservations->first();
            $currentTimeSlotIds = $firstReservation?->reservedSlots->pluck('time_slots_id')->toArray() ?? [];
            $currentNumGuests   = $firstReservation?->num_guests ?? $currentNumGuests;
        }

        return compact('currentTableIds', 'currentTimeSlotIds', 'currentNumGuests');
    }

    /**
     * Update event details and sync the linked system reservations (date, guests, tables, time-slots).
     */
    public function updateEvent(Event $event, array $data): Event
    {
        DB::transaction(function () use ($event, $data) {
            $event->update([
                'event_name'         => $data['event_name'],
                'event_descriptions' => $data['event_descriptions'] ?? null,
                'event_fee'          => $data['event_fee'],
                'max_participants'   => $data['max_participants'],
                'event_date'         => $data['event_date'],
            ]);

            $systemMember = Member::where('email', 'event-' . $event->event_id . '@system.local')->first();
            if (!$systemMember) {
                return;
            }

            // Delete all existing system reservations for this event
            $existing = Reservation::where('member_id', $systemMember->member_id)->get();
            foreach ($existing as $reservation) {
                $reservation->loyaltyTransactions()->delete();
                $reservation->reservedSlots()->delete();
                $reservation->delete();
            }

            // Re-create reservations for the updated table / time-slot selection
            $newDate   = Carbon::parse($data['event_date'])->toDateString();
            $numGuests = (int) ($data['num_guests'] ?? $data['max_participants']);

            foreach ((array) ($data['table_id'] ?? []) as $tableId) {
                $reservation = Reservation::create([
                    'member_id'  => $systemMember->member_id,
                    'event_id'   => $event->event_id,
                    'date'       => $newDate,
                    'num_guests' => $numGuests,
                ]);

                foreach ((array) ($data['time_slots_id'] ?? []) as $timeSlotId) {
                    ReservedSlot::create([
                        'reservation_id' => $reservation->reservation_id,
                        'table_id'       => $tableId,
                        'time_slots_id'  => $timeSlotId,
                        'source_type'    => 'RESERVATION',
                    ]);
                }
            }
        });

        return $event->fresh();
    }

    /**
     * Delete an event and all associated system reservations and the system member.
     * Event registrations are removed via cascade.
     */
    public function deleteEvent(Event $event): void
    {
        DB::transaction(function () use ($event) {
            $systemMember = Member::where('email', 'event-' . $event->event_id . '@system.local')->first();

            if ($systemMember) {
                $reservations = Reservation::where('member_id', $systemMember->member_id)->get();
                foreach ($reservations as $reservation) {
                    $reservation->loyaltyTransactions()->delete();
                    $reservation->reservedSlots()->delete();
                    $reservation->delete();
                }
                $systemMember->delete();
            }

            $event->registrations()->delete();
            $event->delete();
        });
    }
}
