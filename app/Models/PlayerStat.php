<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlayerStat extends Model
{
    use HasFactory, SoftDeletes;

        public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'id');
    }

    public function played_for()
    {
        return $this->belongsTo(Team::class, 'scored_for', 'id');
    }

    public function home_team()
    {
        return $this->belongsTo(Team::class, 'home', 'id');
    }

    public function away_team()
    {
        return $this->belongsTo(Team::class, 'away', 'id');
    }
}
