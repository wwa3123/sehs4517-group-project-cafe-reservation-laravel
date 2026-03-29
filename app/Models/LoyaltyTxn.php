<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyTxn extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'loyalty_txns';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'txn_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_id',
        'reference_type',
        'txn_type',
        'points',
        'descriptions',
    ];

    /**
     * Get the parent reference model (reservation or event).
     */
    public function reference()
    {
        return $this->morphTo();
    }
}
