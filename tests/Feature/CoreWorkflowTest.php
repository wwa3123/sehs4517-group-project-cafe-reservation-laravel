<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\TimeSlot;
use App\Services\EventService;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoreWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_log_in_and_create_a_reservation_with_loyalty_tokens(): void
    {
        $member = Member::factory()->create(['loyalty_points' => 0]);
        $table = $this->createTable();
        $slot = $this->createTimeSlot();
        $date = Carbon::tomorrow()->toDateString();

        $this->post('/login', ['email' => $member->email, 'password' => 'password123'])
            ->assertRedirect(route('reservations.index'));
        $this->assertAuthenticatedAs($member);

        $this->post(route('reservations.store'), [
            'member_id' => $member->member_id,
            'date' => $date,
            'num_guests' => 2,
            'table_id' => $table->table_id,
            'time_slots_id' => [$slot->time_slots_id],
            'tokens_to_spend' => 0,
        ])->assertRedirect(route('reservation.thankyou'));

        $this->assertDatabaseHas('reservations', [
            'member_id' => $member->member_id,
            'date' => $date,
            'num_guests' => 2,
        ]);
        $this->assertDatabaseHas('reserved_slots', [
            'table_id' => $table->table_id,
            'time_slots_id' => $slot->time_slots_id,
            'reservation_date' => $date,
            'source_type' => 'RESERVATION',
        ]);
        $this->assertDatabaseHas('loyalty_txns', ['points' => 10, 'txn_type' => 'RESERVATION']);
        $this->assertSame(10, $member->fresh()->loyalty_points);
    }

    public function test_reservation_slot_collision_is_rejected(): void
    {
        $member = Member::factory()->create();
        $table = $this->createTable();
        $slot = $this->createTimeSlot();
        $date = Carbon::tomorrow()->toDateString();

        app(ReservationService::class)->createReservation([
            'member_id' => $member->member_id,
            'date' => $date,
            'num_guests' => 2,
            'table_id' => $table->table_id,
            'time_slots_id' => [$slot->time_slots_id],
        ]);

        $this->actingAs($member)
            ->from(route('reservations.create'))
            ->post(route('reservations.store'), [
                'member_id' => $member->member_id,
                'date' => $date,
                'num_guests' => 2,
                'table_id' => $table->table_id,
                'time_slots_id' => [$slot->time_slots_id],
            ])
            ->assertRedirect(route('reservations.create'))
            ->assertSessionHasErrors('time_slots_id.0');

        $this->assertDatabaseCount('reservations', 1);
    }

    public function test_members_can_join_events_but_cannot_access_admin_event_management(): void
    {
        $member = Member::factory()->create(['role' => 'member']);
        $admin = Member::factory()->create(['role' => 'admin']);
        $table = $this->createTable();
        $slot = $this->createTimeSlot();

        $event = app(EventService::class)->createEvent([
            'event_name' => 'Tournament',
            'event_fee' => 2500,
            'max_participants' => 4,
            'event_date' => Carbon::tomorrow()->setTime(18, 0)->toDateTimeString(),
            'table_id' => [$table->table_id],
            'time_slots_id' => [$slot->time_slots_id],
        ]);

        $this->actingAs($member)
            ->post(route('events.join', $event), ['num_tickets' => 2])
            ->assertRedirect(route('events.show', $event));

        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->event_id,
            'member_id' => $member->member_id,
            'num_tickets' => 2,
            'payment_status' => 'PENDING',
        ]);

        $this->actingAs($member)->get(route('events.create'))->assertForbidden();
        $this->actingAs($admin)->get(route('events.create'))->assertOk();
    }

    public function test_admin_can_check_in_and_complete_a_confirmed_reservation(): void
    {
        $admin = Member::factory()->create(['role' => 'admin']);
        $reservation = $this->createReservation();

        $this->actingAs($admin)
            ->patch(route('reservations.status.update', $reservation), ['status' => Reservation::STATUS_CHECKED_IN])
            ->assertRedirect(route('reservations.show', $reservation));

        $this->assertDatabaseHas('reservations', [
            'reservation_id' => $reservation->reservation_id,
            'status' => Reservation::STATUS_CHECKED_IN,
        ]);
        $this->assertNotNull($reservation->fresh()->checked_in_at);

        $this->actingAs($admin)
            ->patch(route('reservations.status.update', $reservation), ['status' => Reservation::STATUS_COMPLETED])
            ->assertRedirect(route('reservations.show', $reservation));

        $this->assertDatabaseHas('reservations', [
            'reservation_id' => $reservation->reservation_id,
            'status' => Reservation::STATUS_COMPLETED,
        ]);
        $this->assertNotNull($reservation->fresh()->completed_at);
    }

    public function test_members_cannot_change_reservation_status_and_invalid_transitions_are_rejected(): void
    {
        $member = Member::factory()->create(['role' => 'member']);
        $admin = Member::factory()->create(['role' => 'admin']);
        $reservation = $this->createReservation($member);

        $this->actingAs($member)
            ->patch(route('reservations.status.update', $reservation), ['status' => Reservation::STATUS_CHECKED_IN])
            ->assertForbidden();

        $this->actingAs($admin)
            ->from(route('reservations.show', $reservation))
            ->patch(route('reservations.status.update', $reservation), ['status' => Reservation::STATUS_COMPLETED])
            ->assertRedirect(route('reservations.show', $reservation))
            ->assertSessionHasErrors('status');

        $this->assertDatabaseHas('reservations', [
            'reservation_id' => $reservation->reservation_id,
            'status' => Reservation::STATUS_CONFIRMED,
        ]);
    }

    public function test_cancelling_a_reservation_releases_its_slots_for_a_new_booking(): void
    {
        $admin = Member::factory()->create(['role' => 'admin']);
        $member = Member::factory()->create();
        $table = $this->createTable();
        $slot = $this->createTimeSlot();
        $date = Carbon::tomorrow()->toDateString();
        $service = app(ReservationService::class);
        $reservation = $service->createReservation([
            'member_id' => $member->member_id,
            'date' => $date,
            'num_guests' => 2,
            'table_id' => $table->table_id,
            'time_slots_id' => [$slot->time_slots_id],
        ]);

        $this->actingAs($admin)
            ->patch(route('reservations.status.update', $reservation), ['status' => Reservation::STATUS_CANCELLED])
            ->assertRedirect(route('reservations.show', $reservation));

        $this->assertDatabaseHas('reservations', [
            'reservation_id' => $reservation->reservation_id,
            'status' => Reservation::STATUS_CANCELLED,
        ]);
        $this->assertDatabaseMissing('reserved_slots', ['reservation_id' => $reservation->reservation_id]);

        $replacement = $service->createReservation([
            'member_id' => Member::factory()->create()->member_id,
            'date' => $date,
            'num_guests' => 2,
            'table_id' => $table->table_id,
            'time_slots_id' => [$slot->time_slots_id],
        ]);

        $this->assertSame(Reservation::STATUS_CONFIRMED, $replacement->status);
    }

    public function test_cancelled_future_reservations_are_shown_in_member_visit_history(): void
    {
        $member = Member::factory()->create();
        $reservation = $this->createReservation($member);

        app(ReservationService::class)->transitionStatus($reservation, Reservation::STATUS_CANCELLED);

        $this->actingAs($member)
            ->get(route('reservation.history'))
            ->assertOk()
            ->assertSee('Past')
            ->assertSee('Cancelled');
    }

    public function test_no_show_reservations_release_their_table_slots(): void
    {
        $admin = Member::factory()->create(['role' => 'admin']);
        $reservation = $this->createReservation();

        $this->actingAs($admin)
            ->patch(route('reservations.status.update', $reservation), ['status' => Reservation::STATUS_NO_SHOW])
            ->assertRedirect(route('reservations.show', $reservation));

        $this->assertDatabaseHas('reservations', [
            'reservation_id' => $reservation->reservation_id,
            'status' => Reservation::STATUS_NO_SHOW,
        ]);
        $this->assertDatabaseMissing('reserved_slots', ['reservation_id' => $reservation->reservation_id]);
    }

    private function createReservation(?Member $member = null): Reservation
    {
        $member ??= Member::factory()->create();
        $table = $this->createTable();
        $slot = $this->createTimeSlot();

        return app(ReservationService::class)->createReservation([
            'member_id' => $member->member_id,
            'date' => Carbon::tomorrow()->toDateString(),
            'num_guests' => 2,
            'table_id' => $table->table_id,
            'time_slots_id' => [$slot->time_slots_id],
        ]);
    }

    private function createTable(): Table
    {
        return Table::create([
            'name' => 'Test Table',
            'type' => 'Standard',
            'capacity' => 4,
            'min_players' => 1,
            'min_time' => 60,
        ]);
    }

    private function createTimeSlot(): TimeSlot
    {
        return TimeSlot::create(['start_time' => '18:00:00', 'end_time' => '20:00:00']);
    }
}
