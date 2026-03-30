<?php

namespace App\Models;

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
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

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
}
