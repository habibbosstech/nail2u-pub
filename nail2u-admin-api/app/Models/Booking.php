<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    //    public function Service()
    //    {
    //        return $this->belongsTo(Service::class);
    //    }

    public function Transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function BookingService()
    {
        return $this->belongsToMany(Service::class, 'booking_services')->withPivot('service_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'artist_id');
    }

    public function Client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function Scheduler()
    {
        return $this->belongsTo(Scheduler::class, 'started_at');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format("d/m/Y");
    }
}
