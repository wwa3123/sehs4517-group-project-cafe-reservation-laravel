<?php

namespace Database\Seeders;

use App\Models\LoyaltyTxn;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoyaltyTxnSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LoyaltyTxn::create([
            'reference_id' => 1,
            'reference_type' => 'Reservation',
            'txn_type' => 'RESERVATION',
            'points' => 50.00,
            'descriptions' => 'Points earned from reservation',
        ]);

        LoyaltyTxn::create([
            'reference_id' => 1,
            'reference_type' => 'Event',
            'txn_type' => 'EVENT',
            'points' => 100.00,
            'descriptions' => 'Points earned from event registration',
        ]);
    }
}
