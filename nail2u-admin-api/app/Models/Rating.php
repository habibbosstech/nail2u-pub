<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    public function getCreatedAtAttribute($date)
    {
        return date("Y-m-d", strtotime($date));
    }

    function user()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
