<?php

namespace Database\Seeders;

use App\Models\FeaturedGame;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeaturedGameSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FeaturedGame::create([
            'game_id' => 1,
        ]);

        FeaturedGame::create([
            'game_id' => 2,
        ]);
    }
}
