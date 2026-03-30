<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $primaryKey = 'table_id';

    protected $fillable = [
        'name',
        'type',
        'capacity',
        'photo_url',
        'description',
        'min_players',
        'min_time',
    ];

    public function reservedSlots()
    {
        return $this->hasMany(ReservedSlot::class, 'table_id');
    }
}
