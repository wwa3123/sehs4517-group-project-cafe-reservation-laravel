<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $primaryKey = 'reservation_id';

    protected $fillable = [
        'member_id',
        'date',
        'num_guests',
        'discount_tokens_used',
        'discount_amount_saved',
    ];

    protected $casts = [
        'date' => 'date',
        'discount_amount_saved' => 'decimal:2',
    ];

    protected function date(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Carbon::parse($value)->toDateString(),
        );
    }

    /**
     * Get the member that owns the reservation.
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * Get the reserved slots for the reservation.
     */
    public function reservedSlots()
    {
        return $this->hasMany(ReservedSlot::class, 'reservation_id', 'reservation_id');
    }

    /**
     * Get all loyalty transactions for this reservation.
     */
    public function loyaltyTransactions()
    {
        return $this->morphMany(LoyaltyTxn::class, 'reference');
    }
}
