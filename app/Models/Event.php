<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'event_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_name',
        'event_descriptions',
        'event_fee',
        'max_participants',
        'event_date',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    /**
     * Get the registrations for the event.
     */
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class, 'event_id', 'event_id');
    }

    /**
     * Get reservations linked to this event.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'event_id', 'event_id');
    }

    /**
     * Get all the event's loyalty transactions.
     */
    public function loyaltyTransactions()
    {
        return $this->morphMany(LoyaltyTxn::class, 'reference');
    }
}
