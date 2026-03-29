<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservedSlot extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reserved_slots';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'reserved_slots_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'time_slots_id',
        'source_id',
        'source_type',
        'table_id',
    ];

    /**
     * Get the time slot for the reserved slot.
     */
    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class, 'time_slots_id', 'time_slots_id');
    }

    /**
     * Get the table for the reserved slot.
     */
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id', 'table_id');
    }

    /**
     * Get the parent source model (reservation or event).
     */
    public function source()
    {
        return $this->morphTo();
    }
}
