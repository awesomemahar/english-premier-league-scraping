<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPrediction extends Model
{
    use HasFactory, SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function fixture()
    {
        return $this->belongsTo(Fixture::class, 'fixture_id', 'id');
    }

    public function prediction_result()
    {
        return $this->hasOne(UserPredictionResult::class);
    }
}
