<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeAwayResult extends Model
{
    use HasFactory, SoftDeletes;

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }
}
