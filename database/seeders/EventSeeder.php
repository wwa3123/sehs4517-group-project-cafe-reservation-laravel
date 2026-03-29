<?php

namespace Database\Seeders;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'event_name' => 'Strategy Game Tournament',
            'event_descriptions' => 'Annual tournament featuring strategic board games',
            'event_fee' => 2500,
            'max_participants' => 16,
            'event_date' => Carbon::now()->addDays(15)->toDateTimeString(),
        ]);

        Event::create([
            'event_name' => 'Family Game Night',
            'event_descriptions' => 'Fun games for the whole family',
            'event_fee' => 1000,
            'max_participants' => 20,
            'event_date' => Carbon::now()->addDays(7)->toDateTimeString(),
        ]);
    }
}
