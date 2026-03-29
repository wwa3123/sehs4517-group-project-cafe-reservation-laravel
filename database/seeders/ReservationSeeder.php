<?php

namespace Database\Seeders;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reservation::create([
            'member_id' => 1,
            'date' => Carbon::now()->addDays(3)->setTime(14, 0),
            'num_guests' => 4,
        ]);

        Reservation::create([
            'member_id' => 2,
            'date' => Carbon::now()->addDays(5)->setTime(18, 0),
            'num_guests' => 6,
        ]);

        Reservation::create([
            'member_id' => 3,
            'date' => Carbon::now()->addDays(7)->setTime(19, 0),
            'num_guests' => 2,
        ]);
    }
}
