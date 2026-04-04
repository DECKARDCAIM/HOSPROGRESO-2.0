<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'second_name',
        'third_name',
        'first_last_name',
        'second_last_name',
        'married_last_name',
        'email',
        'password',
        'role_id',
        'is_active',
        'profile_photo_path',
        'banner_photo_path',
        'theme_preference',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted()
    {
        $flushCache = fn () => Cache::tags(['users'])->flush();
        static::saved($flushCache);
        static::deleted($flushCache);
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getAvatarUrlAttribute()
    {
        return $this->profile_photo_path ? asset('storage/'.$this->profile_photo_path) : null;
    }

    public function getBannerUrlAttribute()
    {
        return $this->banner_photo_path ? asset('storage/'.$this->banner_photo_path) : null;
    }

    public function readReleases()
    {
        return $this->belongsToMany(Release::class, 'release_user')->withPivot('read_at')->withTimestamps();
    }
}
