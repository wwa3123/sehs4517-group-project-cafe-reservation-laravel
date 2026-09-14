<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservedSlot extends Model
{
    use HasFactory;

    protected $primaryKey = 'reserved_slots_id';

    protected $fillable = [
        'time_slots_id',
        'reservation_id',
        'event_id',
        'source_type',
        'table_id',
        'reservation_date',
    ];

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class, 'time_slots_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
}
