<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\ReservedSlot;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    public function createReservation(array $data)
    {
        return DB::transaction(function () use ($data) {
            $reservation = Reservation::create([
                'member_id' => $data['member_id'],
                'date' => $data['date'],
                'num_guests' => $data['num_guests'],
            ]);

            ReservedSlot::create([
                'reservation_id' => $reservation->reservation_id,
                'table_id' => $data['table_id'],
                'time_slots_id' => $data['time_slots_id'],
                'source_type' => 'RESERVATION',
            ]);

            return $reservation;
        });
    }
}
