<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorldcupFixture extends Model
{
    use HasFactory;

    protected $table = 'worldcup_fixtures';

    public function home_team()
    {
        return $this->belongsTo(WorldcupTeam::class, 'team_1', 'id');
    }

    public function away_team()
    {
        return $this->belongsTo(WorldcupTeam::class, 'team_2', 'id');
    }

}
