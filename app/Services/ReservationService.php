<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Member;
use App\Models\Reservation;
use App\Models\ReservedSlot;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    private const TOKENS_PER_RESERVATION = 10;

    /**
     * Check whether a time-slot is already booked for a given table and date.
     */
    public function isSlotBooked(int $tableId, int $timeSlotId, string $date): bool
    {
        return ReservedSlot::where('table_id', $tableId)
            ->where('time_slots_id', $timeSlotId)
            ->whereHas('reservation', fn ($q) => $q->whereDate('date', $date))
            ->exists();
    }

    public function createReservation(array $data): Reservation
    {
        return DB::transaction(function () use ($data) {
            $memberId = (int) $data['member_id'];

            $reservation = Reservation::create([
                'member_id'  => $memberId,
                'event_id'   => isset($data['event_id']) ? (int) $data['event_id'] : null,
                'date'       => $data['date'],
                'num_guests' => $data['num_guests'],
            ]);

            foreach ($data['time_slots_id'] as $timeSlotId) {
                ReservedSlot::create([
                    'reservation_id' => $reservation->reservation_id,
                    'table_id'       => $data['table_id'],
                    'time_slots_id'  => $timeSlotId,
                    'source_type'    => 'RESERVATION',
                ]);
            }

            $reservation->loyaltyTransactions()->create([
                'txn_type'     => 'RESERVATION',
                'points'       => self::TOKENS_PER_RESERVATION,
                'descriptions' => 'Loyalty tokens earned from reservation #' . $reservation->reservation_id,
            ]);

            Member::where('member_id', $memberId)->increment('loyalty_points', self::TOKENS_PER_RESERVATION);

            return $reservation->load('member', 'loyaltyTransactions');
        });
    }

    public function updateReservation(Reservation $reservation, array $data): void
    {
        DB::transaction(function () use ($reservation, $data) {
            $reservation->update([
                'date'       => $data['date'],
                'num_guests' => $data['num_guests'],
            ]);

            $reservation->reservedSlots()->delete();
            foreach ($data['time_slots_id'] as $timeSlotId) {
                ReservedSlot::create([
                    'reservation_id' => $reservation->reservation_id,
                    'table_id'       => $data['table_id'],
                    'time_slots_id'  => $timeSlotId,
                    'source_type'    => 'RESERVATION',
                ]);
            }
        });
    }

    public function deleteReservation(Reservation $reservation): void
    {
        $totalPoints = (int) $reservation->loyaltyTransactions()->sum('points');
        if ($totalPoints > 0) {
            Member::where('member_id', $reservation->member_id)
                ->decrement('loyalty_points', $totalPoints);
        }

        $reservation->loyaltyTransactions()->delete();
        $reservation->delete();
    }

    /**
     * Optionally apply a loyalty discount, then build the thank-you session data array.
     */
    public function buildThankYouData(Reservation $reservation, int $tokensToSpend): array
    {
        $earnedTokens    = (int) optional($reservation->loyaltyTransactions->first())->points;
        $discountApplied = 0;

        if ($tokensToSpend > 0 && $reservation->member) {
            $reservation->load('member');
            $applied = LoyaltyRedemptionService::applyDiscount($reservation, $reservation->member, $tokensToSpend);
            if ($applied) {
                $discountApplied = LoyaltyRedemptionService::calculateDiscount($tokensToSpend);
            }
        }

        $reservation->member?->refresh();

        $firstSlot = optional($reservation->reservedSlots->load('timeSlot')->first())->timeSlot;
        $table     = optional($reservation->reservedSlots->first())->table ?? $reservation->table;

        $timeLabel = $firstSlot
            ? Carbon::parse($firstSlot->start_time)->format('g:i A') . ' – ' . Carbon::parse($firstSlot->end_time)->format('g:i A')
            : 'N/A';

        $gameSuggestions = Game::inRandomOrder()->limit(3)->pluck('title')->toArray();
        if (empty($gameSuggestions)) {
            $gameSuggestions = ['Catan', 'Ticket to Ride', 'Codenames'];
        }

        return [
            'email'           => $reservation->member->email,
            'date'            => Carbon::parse($reservation->date)->format('F j, Y'),
            'timeSlot'        => $timeLabel,
            'table'           => optional($table)->name ?? 'Table',
            'earnedTokens'    => $earnedTokens,
            'discountApplied' => $discountApplied,
            'gameSuggestions' => $gameSuggestions,
        ];
    }
}
