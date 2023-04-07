<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function player_stats()
    {
        return $this->hasMany(PlayerStat::class,'scored_for','id');
    }

    public function season()
    {
        return $this->belongsToMany(Season::class);
    }
}
