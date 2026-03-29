<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Call all model seeders
        $this->call([
            MemberSeeder::class,
            GameSeeder::class,
            TableSeeder::class,
            TimeSlotSeeder::class,
            MenuItemSeeder::class,
            EventSeeder::class,
            FeaturedGameSeeder::class,
            EventRegistrationSeeder::class,
            ReservationSeeder::class,
            LoyaltyTxnSeeder::class,
            ReservedSlotSeeder::class,
        ]);
    }
}
