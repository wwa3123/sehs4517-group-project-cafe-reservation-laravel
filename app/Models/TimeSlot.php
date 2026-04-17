<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'time_slots_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'start_time',
        'end_time',
    ];

    /**
     * Get the reserved slots for the time slot.
     */
    public function reservedSlots()
    {
        return $this->hasMany(ReservedSlot::class, 'time_slots_id', 'time_slots_id');
    }
}
