<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // public function service()
    // {
    //     return $this->belongsTo(Service::class);
    // }

    public function BookingService()
    {
        return $this->belongsToMany(Service::class, 'booking_services')->withPivot('service_id');
    }

    public function Transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function Client()
    {
        return $this->belongsTo(User::class);
    }
}
