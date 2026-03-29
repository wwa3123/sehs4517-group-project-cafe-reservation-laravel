<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuItem::create([
            'item_name' => 'Espresso',
            'category' => 'Coffee',
            'description' => 'Rich and bold espresso shot',
            'is_available' => true,
            'photo_url' => 'https://example.com/espresso.jpg',
        ]);

        MenuItem::create([
            'item_name' => 'Cappuccino',
            'category' => 'Coffee',
            'description' => 'Smooth cappuccino with creamy foam',
            'is_available' => true,
            'photo_url' => 'https://example.com/cappuccino.jpg',
        ]);

        MenuItem::create([
            'item_name' => 'Croissant',
            'category' => 'Pastry',
            'description' => 'Buttery French croissant',
            'is_available' => true,
            'photo_url' => 'https://example.com/croissant.jpg',
        ]);

        MenuItem::create([
            'item_name' => 'Chocolate Chip Cookie',
            'category' => 'Dessert',
            'description' => 'Classic homemade chocolate chip cookie',
            'is_available' => true,
            'photo_url' => 'https://example.com/cookie.jpg',
        ]);
    }
}
