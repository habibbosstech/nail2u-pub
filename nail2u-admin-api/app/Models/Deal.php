<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use HasFactory,SoftDeletes;

    protected $appends = [
        'absolute_image_url'
    ];

    public function Services()
    {
        return $this->belongsToMany(Service::class, 'deal_service', 'service_id');
    }

    public function getAbsoluteImageUrlAttribute()
    {
        return url($this->attributes['image_url']);
    }
}
