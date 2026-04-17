<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TimeSlot::create([
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
        ]);

        TimeSlot::create([
            'start_time' => '15:00:00',
            'end_time' => '16:00:00',
        ]);

        TimeSlot::create([
            'start_time' => '16:00:00',
            'end_time' => '17:00:00',
        ]);

        TimeSlot::create([
            'start_time' => '17:00:00',
            'end_time' => '18:00:00',
        ]);

        TimeSlot::create([
            'start_time' => '18:00:00',
            'end_time' => '19:00:00',
        ]);

        TimeSlot::create([
            'start_time' => '19:00:00',
            'end_time' => '20:00:00',
        ]);

        TimeSlot::create([
            'start_time' => '20:00:00',
            'end_time' => '21:00:00',
        ]);

        TimeSlot::create([
            'start_time' => '21:00:00',
            'end_time' => '22:00:00',
        ]);

        TimeSlot::create([
            'start_time' => '22:00:00',
            'end_time' => '23:00:00',
        ]);
    }
}
