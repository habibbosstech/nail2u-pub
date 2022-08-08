<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistService extends Model
{
    use HasFactory;

    public function Services()
    {
        return $this->belongsToMany(Service::class, 'artist_services');
    }

    public function Artist()
    {
        return $this->belongsToMany(User::class, 'artist_services');
    }
}
