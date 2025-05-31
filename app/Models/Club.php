<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $table = 'clubs';

    protected $fillable = [
        'name',
        'logo',
        'league_id',
        'matches_played',
        'wins',
        'draws',
        'losses',
        'goals_for',
        'goals_against',
        'points',
    ];

    public function league()
    {
        return $this->belongsTo(League::class);
    }
}