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
            'source_id' => 1,
            'source_type' => 'RESERVATION',
            'table_id' => 1,
        ]);

        ReservedSlot::create([
            'time_slots_id' => 2,
            'source_id' => 2,
            'source_type' => 'RESERVATION',
            'table_id' => 2,
        ]);

        ReservedSlot::create([
            'time_slots_id' => 3,
            'source_id' => 1,
            'source_type' => 'EVENT',
            'table_id' => 1,
        ]);
    }
}
