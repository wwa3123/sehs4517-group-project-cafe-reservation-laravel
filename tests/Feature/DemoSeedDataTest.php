<?php

namespace Tests\Feature;

use App\Models\Member;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoSeedDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seed_data_uses_event_and_reservation_workflows(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('events', 2);
        $this->assertDatabaseCount('reservations', 3);
        $this->assertDatabaseCount('loyalty_txns', 3);
        $this->assertDatabaseCount('reserved_slots', 13);
        $this->assertDatabaseMissing('members', ['role' => 'system']);
        $this->assertDatabaseMissing('reserved_slots', ['source_type' => 'EVENT', 'reservation_id' => 1]);
        $this->assertDatabaseMissing('reserved_slots', ['source_type' => 'RESERVATION', 'event_id' => 1]);
    }

    public function test_email_availability_endpoint_is_not_exposed_and_logout_requires_post(): void
    {
        $member = Member::factory()->create();

        $this->get('/check-email?email=member@example.com')->assertNotFound();
        $this->get('/logout')->assertMethodNotAllowed();
        $this->actingAs($member)->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
