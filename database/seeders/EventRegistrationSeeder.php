<?php

namespace Database\Seeders;

use App\Models\EventRegistration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventRegistrationSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EventRegistration::create([
            'event_id' => 1,
            'member_id' => 1,
            'num_tickets' => 2,
            'payment_status' => 'COMPLETED',
        ]);

        EventRegistration::create([
            'event_id' => 1,
            'member_id' => 2,
            'num_tickets' => 1,
            'payment_status' => 'COMPLETED',
        ]);

        EventRegistration::create([
            'event_id' => 2,
            'member_id' => 3,
            'num_tickets' => 3,
            'payment_status' => 'PENDING',
        ]);
    }
}
