<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $table = 'leagues';
    protected $primaryKey = 'league_id';
    public $timestamps = false;

    protected $fillable = [
        'name', 'country', 'logo', 'year_of_play',
    ];
}
