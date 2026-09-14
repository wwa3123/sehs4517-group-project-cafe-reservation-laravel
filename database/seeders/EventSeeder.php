<?php

namespace Database\Seeders;

use App\Models\Table;
use App\Models\TimeSlot;
use App\Services\EventService;
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
        app(EventService::class)->createEvent([
            'event_name' => 'Strategy Game Tournament',
            'event_descriptions' => 'Annual tournament featuring strategic board games',
            'event_fee' => 2500,
            'max_participants' => 16,
            'event_date' => Carbon::now()->addDays(15)->toDateTimeString(),
            'table_id' => Table::whereIn('name', ['Table 1', 'Table 2'])->pluck('table_id')->all(),
            'time_slots_id' => TimeSlot::whereIn('start_time', ['18:00:00', '19:00:00'])->pluck('time_slots_id')->all(),
        ]);

        app(EventService::class)->createEvent([
            'event_name' => 'Family Game Night',
            'event_descriptions' => 'Fun games for the whole family',
            'event_fee' => 1000,
            'max_participants' => 20,
            'event_date' => Carbon::now()->addDays(7)->toDateTimeString(),
            'table_id' => Table::pluck('table_id')->all(),
            'time_slots_id' => TimeSlot::whereIn('start_time', ['20:00:00', '21:00:00'])->pluck('time_slots_id')->all(),
        ]);
    }
}
