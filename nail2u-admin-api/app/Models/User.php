<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Carbon\Carbon;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'email',
    ];

    protected $appends = [
        'absolute_cv_url',
        'absolute_image_url',
        'avg_rating',
        'created_at'
    ];
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function identities()
    {
        return $this->hasMany(SocialIdentity::class);
    }

    public function setting()
    {
        return $this->hasOne(AdminSetting::class, 'user_id');
    }

    public function transections()
    {
        return $this->hasMany(Transaction::class, 'receiver_id');
    }

    public function FavouriteArtist()
    {
        return $this->belongsToMany(User::class, 'favourite_artist')->withPivot('artist_id');
    }

    public function getAbsoluteCvUrlAttribute()
    {
        return url($this->attributes['cv_url']);
    }

    public function getAbsoluteImageUrlAttribute()
    {
        return url($this->attributes['image_url']);
    }

    public function jobs()
    {
        return $this->hasMany(Booking::class, 'artist_id');
    }

    public function reviews()
    {
        return $this->hasMany(Rating::class, 'artist_id');
    }

    public function portfolio()
    {
        return $this->hasMany(Portfolio::class, 'artist_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'artist_services', 'artist_id')->select(['name', 'price']);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getAvgRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 2);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'artist_id');
    }

    public function getAddressAttribute($value)
    {
        //return unserialize($value);
    }

    public function adminAddedArtist()
    {
        return $this->hasMany(User::class, 'approved_by');
    }

    public function adminAddedServices()
    {
        return $this->hasMany(Service::class, 'user_id');
    }

    public function adminApprovePayment()
    {
        return $this->hasMany(Transaction::class, 'approved_by');
    }

    public function task()
    {
        return $this->hasMany(Task::class, 'user_id');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
            $model->updated_at = $model->freshTimestamp();
        });
    }
}
