<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Reservation;
use App\Models\ReservedSlot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ReservationService
{
    private const TOKENS_PER_RESERVATION = 10;
    private const DUMMY_MEMBER_EMAIL = 'event-reservation-dummy@system.local';

    private function resolveMemberId(array $data): int
    {
        if (!empty($data['event_id'])) {
            $dummy = Member::firstOrCreate(
                ['email' => self::DUMMY_MEMBER_EMAIL],
                [
                    'first_name' => 'Event',
                    'last_name' => 'Placeholder',
                    'password_hash' => Hash::make('dummy-member-password'),
                    'role' => 'system',
                    'subscribe_events' => false,
                    'loyalty_points' => 0,
                ]
            );

            return (int) $dummy->member_id;
        }

        return (int) $data['member_id'];
    }

    public function createReservation(array $data)
    {
        return DB::transaction(function () use ($data) {
            $memberId = $this->resolveMemberId($data);

            $reservation = Reservation::create([
                'member_id' => $memberId,
                'event_id' => $data['event_id'] ?? null,
                'date' => $data['date'],
                'num_guests' => $data['num_guests'],
            ]);

            foreach ($data['time_slots_id'] as $timeSlotId) {
                ReservedSlot::create([
                    'reservation_id' => $reservation->reservation_id,
                    'table_id' => $data['table_id'],
                    'time_slots_id' => $timeSlotId,
                    'source_type' => !empty($data['event_id']) ? 'EVENT' : 'RESERVATION',
                ]);
            }

            if (empty($data['event_id'])) {
                $earnedTokens = self::TOKENS_PER_RESERVATION;

                $reservation->loyaltyTransactions()->create([
                    'txn_type' => 'RESERVATION',
                    'points' => $earnedTokens,
                    'descriptions' => 'Loyalty tokens earned from reservation #'.$reservation->reservation_id,
                ]);

                Member::where('member_id', $memberId)->increment('loyalty_points', $earnedTokens);
            }

            return $reservation->load('member', 'event', 'loyaltyTransactions');
        });
    }
}
