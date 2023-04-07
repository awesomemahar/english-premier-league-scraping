<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPredictionResult extends Model
{
    use HasFactory, SoftDeletes;

    public function user_prediction()
    {
        return $this->belongsTo(UserPrediction::class, 'user_prediction_id', 'id');
    }
}
