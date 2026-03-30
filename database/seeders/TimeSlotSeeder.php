<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use Carbon\Carbon;
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
            'start_time' => Carbon::now()->setTime(14, 0),
            'end_time' => Carbon::now()->setTime(15, 0),
        ]);

        TimeSlot::create([
            'start_time' => Carbon::now()->setTime(15, 0),
            'end_time' => Carbon::now()->setTime(16, 0),
        ]);

        TimeSlot::create([
            'start_time' => Carbon::now()->setTime(16, 0),
            'end_time' => Carbon::now()->setTime(17, 0),
        ]);

        TimeSlot::create([
            'start_time' => Carbon::now()->setTime(17, 0),
            'end_time' => Carbon::now()->setTime(18, 0),
        ]);

        TimeSlot::create([
            'start_time' => Carbon::now()->setTime(18, 0),
            'end_time' => Carbon::now()->setTime(19, 0),
        ]);

        TimeSlot::create([
            'start_time' => Carbon::now()->setTime(19, 0),
            'end_time' => Carbon::now()->setTime(20, 0),
        ]);

        TimeSlot::create([
            'start_time' => Carbon::now()->setTime(20, 0),
            'end_time' => Carbon::now()->setTime(21, 0),
        ]);

        TimeSlot::create([
            'start_time' => Carbon::now()->setTime(21, 0),
            'end_time' => Carbon::now()->setTime(22, 0),
        ]);

        TimeSlot::create([
            'start_time' => Carbon::now()->setTime(22, 0),
            'end_time' => Carbon::now()->setTime(23, 0),
        ]);
    }
}
