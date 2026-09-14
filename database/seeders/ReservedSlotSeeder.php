<?php

namespace Database\Seeders;

use App\Models\ReservedSlot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservedSlotSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReservedSlot::create([
            'time_slots_id' => 1,
            'reservation_id' => 1,
            'source_type' => 'RESERVATION',
            'table_id' => 1,
            'reservation_date' => now()->addDays(3)->toDateString(),
        ]);

        ReservedSlot::create([
            'time_slots_id' => 2,
            'reservation_id' => 2,
            'source_type' => 'RESERVATION',
            'table_id' => 2,
            'reservation_date' => now()->addDays(5)->toDateString(),
        ]);

        ReservedSlot::create([
            'time_slots_id' => 3,
            'event_id' => 1,
            'source_type' => 'EVENT',
            'table_id' => 1,
            'reservation_date' => now()->addDays(15)->toDateString(),
        ]);
    }
}
