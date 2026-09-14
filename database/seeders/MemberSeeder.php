<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminPassword = app()->environment('local', 'testing')
            ? env('DEMO_ADMIN_PASSWORD', Str::random(40))
            : Str::random(40);

        Member::updateOrCreate(['email' => 'admin@example.com'], [
            'first_name' => 'Admin',
            'last_name' => 'User',
            'address' => '1 Admin Plaza, Springfield',
            'phone' => '555-0100',
            'password_hash' => Hash::make($adminPassword),
            'role' => 'admin',
            'subscribe_events' => false,
            'loyalty_points' => 0,
        ]);

        Member::updateOrCreate(['email' => 'john.doe@example.com'], [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address' => '123 Main St, Springfield',
            'phone' => '555-0101',
            'password_hash' => Hash::make(Str::random(40)),
            'subscribe_events' => true,
            'loyalty_points' => 0,
        ]);

        Member::updateOrCreate(['email' => 'jane.smith@example.com'], [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'address' => '456 Oak Ave, Springfield',
            'phone' => '555-0102',
            'password_hash' => Hash::make(Str::random(40)),
            'subscribe_events' => true,
            'loyalty_points' => 0,
        ]);

        Member::updateOrCreate(['email' => 'bob.johnson@example.com'], [
            'first_name' => 'Bob',
            'last_name' => 'Johnson',
            'address' => '789 Pine Rd, Springfield',
            'phone' => '555-0103',
            'password_hash' => Hash::make(Str::random(40)),
            'subscribe_events' => false,
            'loyalty_points' => 0,
        ]);
    }
}
