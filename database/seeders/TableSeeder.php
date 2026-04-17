<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Table::create([
            'name' => 'Table 1',
            'type' => 'Standard',
            'capacity' => 4,
            'photo_url' => 'images/tables/table1.jpg',
            'description' => 'Standard 4-person gaming table',
            'min_players' => 2,
            'min_time' => 60,
        ]);

        Table::create([
            'name' => 'Table 2',
            'type' => 'Gaming',
            'capacity' => 6,
            'photo_url' => 'images/tables/table2.jpg',
            'description' => 'Large gaming table with built-in cup holders',
            'min_players' => 3,
            'min_time' => 90,
        ]);

        Table::create([
            'name' => 'VIP Table',
            'type' => 'VIP',
            'capacity' => 4,
            'photo_url' => 'images/tables/vip.jpg',
            'description' => 'Premium VIP gaming experience',
            'min_players' => 2,
            'min_time' => 120,
        ]);
    }
}
