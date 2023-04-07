<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TopScorer extends Model
{
    use HasFactory, SoftDeletes;

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'id');
    }
}
