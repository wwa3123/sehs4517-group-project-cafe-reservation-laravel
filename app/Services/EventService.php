<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Member;
use App\Models\ReservedSlot;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EventService
{
    public function __construct(private ReservationService $reservationService) {}

    /**
     * Fetch all events with ticket counts and available slots.
     */
    public function listEvents(): Collection
    {
        return Event::withSum([
            'registrations as registered_tickets' => fn ($q) => $q->where('payment_status', '!=', 'CANCELLED'),
        ], 'num_tickets')
            ->orderBy('event_date')
            ->get()
            ->map(function (Event $event) {
                $registered = (int) ($event->registered_tickets ?? 0);
                $event->registered_tickets = $registered;
                $event->available_tickets  = max(0, (int) $event->max_participants - $registered);
                return $event;
            });
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
}
