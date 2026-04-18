<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class LoyaltyRedemptionService
{
    private const TOKENS_PER_DOLLAR = 2; // 2 tokens = $1 discount

    /**
     * Calculate discount amount from tokens
     */
    public static function calculateDiscount(int $tokens): float
    {
        return $tokens / self::TOKENS_PER_DOLLAR;
    }

    /**
     * Get available discount tier options
     */
    public static function getDiscountTiers(int $availableTokens): array
    {
        $tiers = [];
        $tokenSteps = [5, 10, 20, 50, 100];

        foreach ($tokenSteps as $tokens) {
            if ($tokens <= $availableTokens) {
                $tiers[] = [
                    'tokens' => $tokens,
                    'discount' => self::calculateDiscount($tokens),
                ];
            }
        }

        return $tiers;
    }

    /**
     * Apply discount tokens to a reservation
     */
    public static function applyDiscount(Reservation $reservation, Member $member, int $tokensToSpend): bool
    {
        // Ensure the authenticated user can only redeem their own tokens
        if (auth()->check() && auth()->id() !== $member->member_id) {
            return false;
        }

        if ($tokensToSpend <= 0 || $tokensToSpend > $member->loyalty_points) {
            return false;
        }

        $discountAmount = self::calculateDiscount($tokensToSpend);

        DB::transaction(function () use ($reservation, $member, $tokensToSpend, $discountAmount) {
            $reservation->update([
                'discount_tokens_used' => $tokensToSpend,
                'discount_amount_saved' => $discountAmount,
            ]);

            // Create negative loyalty transaction
            $reservation->loyaltyTransactions()->create([
                'txn_type' => 'RESERVATION',
                'points' => -$tokensToSpend,
                'descriptions' => "Loyalty tokens redeemed for $" . number_format($discountAmount, 2) . " discount on reservation #" . $reservation->reservation_id,
            ]);

            // Deduct tokens from member
            $member->decrement('loyalty_points', $tokensToSpend);
        });

        return true;
    }
}
