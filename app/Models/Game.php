<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'game_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'category',
        'min_players',
        'max_players',
        'description',
        'photo_url',
    ];

    /**
     * Get the featured game entries for the game.
     */
    public function featuredGames()
    {
        return $this->hasMany(FeaturedGame::class, 'game_id', 'game_id');
    }
}
