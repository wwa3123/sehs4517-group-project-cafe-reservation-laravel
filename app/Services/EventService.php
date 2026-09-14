<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\ReservedSlot;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EventService
{
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
            $event->available_tickets = max(0, (int) $event->max_participants - $registered);

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
                'event_name' => $data['event_name'],
                'event_descriptions' => $data['event_descriptions'] ?? null,
                'event_fee' => $data['event_fee'],
                'max_participants' => $data['max_participants'],
                'event_date' => $data['event_date'],
            ]);

            $eventDate = Carbon::parse($event->event_date)->toDateString();
            $this->createReservedSlots($event, (array) $data['table_id'], (array) $data['time_slots_id'], $eventDate);

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
            ->where('reservation_date', Carbon::parse($date)->toDateString())
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
            'available' => max(0, (int) $event->max_participants - $registered),
        ];
    }

    /**
     * Register a member for an event. Returns true on success or an error key/message pair on failure.
     *
     * @return true|array{field: string, message: string}
     */
    public function joinEvent(Event $event, int $memberId, int $numTickets): true|array
    {
        return DB::transaction(function () use ($event, $memberId, $numTickets) {
            // Serializing registrations on the event row prevents simultaneous
            // capacity checks from admitting more tickets than are available.
            $lockedEvent = Event::query()
                ->lockForUpdate()
                ->findOrFail($event->event_id);

            $alreadyJoined = EventRegistration::where('event_id', $lockedEvent->event_id)
                ->where('member_id', $memberId)
                ->where('payment_status', '!=', 'CANCELLED')
                ->exists();

            if ($alreadyJoined) {
                return ['field' => 'member_id', 'message' => 'This member has already joined this event.'];
            }

            $registered = (int) EventRegistration::where('event_id', $lockedEvent->event_id)
                ->where('payment_status', '!=', 'CANCELLED')
                ->sum('num_tickets');

            $available = (int) $lockedEvent->max_participants - $registered;

            if ($numTickets > $available) {
                return ['field' => 'num_tickets', 'message' => 'Not enough available tickets for this event.'];
            }

            EventRegistration::create([
                'event_id' => $lockedEvent->event_id,
                'member_id' => $memberId,
                'num_tickets' => $numTickets,
                'payment_status' => 'PENDING',
            ]);

            return true;
        });
    }

    /**
     * Return the current table IDs, time-slot IDs, and participant count,
     * used to pre-populate the edit form.
     *
     * @return array{currentTableIds: array, currentTimeSlotIds: array}
     */
    public function getEditData(Event $event): array
    {
        $reservedSlots = $event->reservedSlots()->get();
        $currentTableIds = $reservedSlots->pluck('table_id')->unique()->values()->toArray();
        $currentTimeSlotIds = $reservedSlots->pluck('time_slots_id')->unique()->values()->toArray();

        return compact('currentTableIds', 'currentTimeSlotIds');
    }

    /**
     * Update event details and replace the linked event table slots.
     */
    public function updateEvent(Event $event, array $data): Event
    {
        DB::transaction(function () use ($event, $data) {
            $event->update([
                'event_name' => $data['event_name'],
                'event_descriptions' => $data['event_descriptions'] ?? null,
                'event_fee' => $data['event_fee'],
                'max_participants' => $data['max_participants'],
                'event_date' => $data['event_date'],
            ]);

            $newDate = Carbon::parse($data['event_date'])->toDateString();
            $event->reservedSlots()->delete();
            $this->createReservedSlots($event, (array) $data['table_id'], (array) $data['time_slots_id'], $newDate);
        });

        return $event->fresh();
    }

    /**
     * Delete an event and all associated table slots.
     * Event registrations are removed via cascade.
     */
    public function deleteEvent(Event $event): void
    {
        DB::transaction(function () use ($event) {
            $event->registrations()->delete();
            $event->reservedSlots()->delete();
            $event->delete();
        });
    }

    private function createReservedSlots(Event $event, array $tableIds, array $timeSlotIds, string $date): void
    {
        foreach ($tableIds as $tableId) {
            foreach ($timeSlotIds as $timeSlotId) {
                ReservedSlot::create([
                    'event_id' => $event->event_id,
                    'table_id' => $tableId,
                    'time_slots_id' => $timeSlotId,
                    'source_type' => 'EVENT',
                    'reservation_date' => $date,
                ]);
            }
        }
    }
}
