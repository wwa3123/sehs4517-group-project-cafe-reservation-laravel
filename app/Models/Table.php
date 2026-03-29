<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'table_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'capacity',
        'photo_url',
        'description',
        'min_players',
        'min_time',
    ];

    /**
     * Get the reserved slots for the table.
     */
    public function reservedSlots()
    {
        return $this->hasMany(ReservedSlot::class, 'table_id', 'table_id');
    }
}
