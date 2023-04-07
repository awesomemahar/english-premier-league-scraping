<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use HasFactory, SoftDeletes;

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }

    public function stats()
    {
        return $this->hasMany(PlayerStat::class);
    }

    public function goals()
    {
        return $this->hasOne(TopScorer::class);
    }


}
