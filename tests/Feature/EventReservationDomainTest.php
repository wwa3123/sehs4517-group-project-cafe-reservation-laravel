<?php

namespace Tests\Feature;

use App\Models\Table;
use App\Models\TimeSlot;
use App\Services\EventService;
use App\Services\ReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventReservationDomainTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_slots_are_owned_by_the_event_without_a_member_reservation_or_loyalty_transaction(): void
    {
        $table = $this->createTable('Event Table');
        $slot = TimeSlot::create(['start_time' => '18:00:00', 'end_time' => '20:00:00']);

        $event = app(EventService::class)->createEvent([
            'event_name' => 'Game Night',
            'event_fee' => 1000,
            'max_participants' => 12,
            'event_date' => '2026-10-01 18:00:00',
            'table_id' => [$table->table_id],
            'time_slots_id' => [$slot->time_slots_id],
        ]);

        $this->assertDatabaseHas('reserved_slots', [
            'event_id' => $event->event_id,
            'reservation_id' => null,
            'source_type' => 'EVENT',
            'reservation_date' => '2026-10-01',
        ]);
        $this->assertDatabaseCount('reservations', 0);
        $this->assertDatabaseCount('loyalty_txns', 0);
        $this->assertDatabaseCount('members', 0);
    }

    public function test_updating_an_event_replaces_only_its_directly_owned_slots(): void
    {
        $firstTable = $this->createTable('First Table');
        $secondTable = $this->createTable('Second Table');
        $firstSlot = TimeSlot::create(['start_time' => '18:00:00', 'end_time' => '20:00:00']);
        $secondSlot = TimeSlot::create(['start_time' => '20:00:00', 'end_time' => '22:00:00']);
        $service = app(EventService::class);

        $event = $service->createEvent([
            'event_name' => 'Game Night',
            'event_fee' => 1000,
            'max_participants' => 12,
            'event_date' => '2026-10-01 18:00:00',
            'table_id' => [$firstTable->table_id],
            'time_slots_id' => [$firstSlot->time_slots_id],
        ]);

        $service->updateEvent($event, [
            'event_name' => 'Updated Game Night',
            'event_fee' => 1500,
            'max_participants' => 16,
            'event_date' => '2026-10-02 20:00:00',
            'table_id' => [$secondTable->table_id],
            'time_slots_id' => [$secondSlot->time_slots_id],
        ]);

        $this->assertDatabaseCount('reserved_slots', 1);
        $this->assertDatabaseHas('reserved_slots', [
            'event_id' => $event->event_id,
            'reservation_id' => null,
            'table_id' => $secondTable->table_id,
            'time_slots_id' => $secondSlot->time_slots_id,
            'source_type' => 'EVENT',
            'reservation_date' => '2026-10-02',
        ]);
    }

    public function test_event_slots_are_unavailable_to_reservations(): void
    {
        $table = $this->createTable('Shared Table');
        $slot = TimeSlot::create(['start_time' => '18:00:00', 'end_time' => '20:00:00']);

        app(EventService::class)->createEvent([
            'event_name' => 'Game Night',
            'event_fee' => 1000,
            'max_participants' => 12,
            'event_date' => '2026-10-01 18:00:00',
            'table_id' => [$table->table_id],
            'time_slots_id' => [$slot->time_slots_id],
        ]);

        $this->assertTrue(app(ReservationService::class)->isSlotBooked(
            $table->table_id,
            $slot->time_slots_id,
            '2026-10-01'
        ));
    }

    private function createTable(string $name): Table
    {
        return Table::create([
            'name' => $name,
            'type' => 'Standard',
            'capacity' => 12,
            'min_players' => 1,
            'min_time' => 60,
        ]);
    }
}
