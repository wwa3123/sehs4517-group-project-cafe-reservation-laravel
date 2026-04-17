<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Game::create([
            'title' => 'Catan',
            'category' => 'Strategy',
            'min_players' => 2,
            'max_players' => 4,
            'description' => 'Build and trade to become the dominant force on the island of Catan.',
            'photo_url' => 'images/games/catan.jpg',
        ]);

        Game::create([
            'title' => 'Ticket to Ride',
            'category' => 'Strategy',
            'min_players' => 2,
            'max_players' => 5,
            'description' => 'Claim railway routes across the continent.',
            'photo_url' => 'images/games/ttr.jpg',
        ]);

        Game::create([
            'title' => 'Carcassonne',
            'category' => 'Family',
            'min_players' => 2,
            'max_players' => 5,
            'description' => 'Build the medieval landscape of southern France.',
            'photo_url' => 'images/games/carcassonne.jpg',
        ]);

        Game::create([
            'title' => 'Dominion',
            'category' => 'Card Game',
            'min_players' => 2,
            'max_players' => 4,
            'description' => 'A deck-building card game where you build your kingdom.',
            'photo_url' => 'images/games/dominion.jpg',
        ]);
    }
}
