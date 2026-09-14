<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Table;
use App\Models\TimeSlot;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $service = app(ReservationService::class);

        $service->createReservation([
            'member_id' => Member::where('email', 'admin@example.com')->valueOrFail('member_id'),
            'date' => Carbon::now()->addDays(3)->toDateString(),
            'num_guests' => 4,
            'table_id' => Table::where('name', 'Table 1')->valueOrFail('table_id'),
            'time_slots_id' => [TimeSlot::where('start_time', '14:00:00')->valueOrFail('time_slots_id')],
        ]);

        $service->createReservation([
            'member_id' => Member::where('email', 'john.doe@example.com')->valueOrFail('member_id'),
            'date' => Carbon::now()->addDays(5)->toDateString(),
            'num_guests' => 6,
            'table_id' => Table::where('name', 'Table 2')->valueOrFail('table_id'),
            'time_slots_id' => [TimeSlot::where('start_time', '15:00:00')->valueOrFail('time_slots_id')],
        ]);

        $service->createReservation([
            'member_id' => Member::where('email', 'jane.smith@example.com')->valueOrFail('member_id'),
            'date' => Carbon::now()->addDays(7)->toDateString(),
            'num_guests' => 2,
            'table_id' => Table::where('name', 'VIP Table')->valueOrFail('table_id'),
            'time_slots_id' => [TimeSlot::where('start_time', '16:00:00')->valueOrFail('time_slots_id')],
        ]);
    }
}
