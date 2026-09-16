<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CHECKED_IN = 'checked_in';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_NO_SHOW = 'no_show';

    protected $primaryKey = 'reservation_id';

    protected $fillable = [
        'member_id',
        'date',
        'num_guests',
        'status',
        'checked_in_at',
        'completed_at',
        'cancelled_at',
        'no_show_at',
        'discount_tokens_used',
        'discount_amount_saved',
    ];

    protected $casts = [
        'date' => 'date',
        'checked_in_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'no_show_at' => 'datetime',
        'discount_amount_saved' => 'decimal:2',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_CONFIRMED,
            self::STATUS_CHECKED_IN,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
            self::STATUS_NO_SHOW,
        ];
    }

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
