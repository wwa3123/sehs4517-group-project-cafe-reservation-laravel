<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Reservation;
use App\Models\ReservedSlot;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    private const TOKENS_PER_RESERVATION = 10;

    public function createReservation(array $data)
    {
        return DB::transaction(function () use ($data) {
            $reservation = Reservation::create([
                'member_id' => $data['member_id'],
                'date' => $data['date'],
                'num_guests' => $data['num_guests'],
            ]);

            foreach ($data['time_slots_id'] as $timeSlotId) {
                ReservedSlot::create([
                    'reservation_id' => $reservation->reservation_id,
                    'table_id' => $data['table_id'],
                    'time_slots_id' => $timeSlotId,
                    'source_type' => 'RESERVATION',
                ]);
            }

            $earnedTokens = self::TOKENS_PER_RESERVATION;

            $reservation->loyaltyTransactions()->create([
                'txn_type' => 'RESERVATION',
                'points' => $earnedTokens,
                'descriptions' => 'Loyalty tokens earned from reservation #'.$reservation->reservation_id,
            ]);

            Member::where('member_id', $data['member_id'])->increment('loyalty_points', $earnedTokens);

            return $reservation->load('member', 'loyaltyTransactions');
        });
    }
}
