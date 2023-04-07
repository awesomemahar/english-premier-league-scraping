<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fixture extends Model
{
    use HasFactory, SoftDeletes;

    public function home_team()
    {
        return $this->belongsTo(Team::class, 'home', 'id');
    }

    public function away_team()
    {
        return $this->belongsTo(Team::class, 'away', 'id');
    }

    public function predictions()
    {
        return $this->hasMany(UserPrediction::class);
    }
}
