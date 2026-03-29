<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Member::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address' => '123 Main St, Springfield',
            'phone' => '555-0101',
            'email' => 'john.doe@example.com',
            'password_hash' => Hash::make('password123'),
            'subscribe_events' => true,
            'loyalty_points' => 150,
        ]);

        Member::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'address' => '456 Oak Ave, Springfield',
            'phone' => '555-0102',
            'email' => 'jane.smith@example.com',
            'password_hash' => Hash::make('password123'),
            'subscribe_events' => true,
            'loyalty_points' => 300,
        ]);

        Member::create([
            'first_name' => 'Bob',
            'last_name' => 'Johnson',
            'address' => '789 Pine Rd, Springfield',
            'phone' => '555-0103',
            'email' => 'bob.johnson@example.com',
            'password_hash' => Hash::make('password123'),
            'subscribe_events' => false,
            'loyalty_points' => 75,
        ]);
    }
}
