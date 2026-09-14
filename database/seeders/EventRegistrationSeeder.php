<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Member;
use App\Services\EventService;
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
        $service = app(EventService::class);
        $tournament = Event::where('event_name', 'Strategy Game Tournament')->firstOrFail();
        $familyNight = Event::where('event_name', 'Family Game Night')->firstOrFail();

        $registrations = [
            [$tournament, 'admin@example.com', 2, 'COMPLETED'],
            [$tournament, 'john.doe@example.com', 1, 'COMPLETED'],
            [$familyNight, 'jane.smith@example.com', 3, 'PENDING'],
        ];

        foreach ($registrations as [$event, $email, $tickets, $status]) {
            $memberId = Member::where('email', $email)->valueOrFail('member_id');
            $service->joinEvent($event, $memberId, $tickets);
            $event->registrations()
                ->where('member_id', $memberId)
                ->update(['payment_status' => $status]);
        }
    }
}
