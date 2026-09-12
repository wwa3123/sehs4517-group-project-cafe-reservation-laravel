<?php

namespace Database\Seeders;

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
