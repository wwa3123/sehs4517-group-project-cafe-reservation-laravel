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
            'start_time' => Carbon::now()->setTime(10, 0),
            'end_time' => Carbon::now()->setTime(12, 30),
        ]);

        TimeSlot::create([
            'start_time' => Carbon::now()->setTime(13, 0),
            'end_time' => Carbon::now()->setTime(15, 30),
        ]);

        TimeSlot::create([
            'start_time' => Carbon::now()->setTime(16, 0),
            'end_time' => Carbon::now()->setTime(18, 30),
        ]);

        TimeSlot::create([
            'start_time' => Carbon::now()->setTime(19, 0),
            'end_time' => Carbon::now()->setTime(21, 30),
        ]);
    }
}
