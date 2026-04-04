<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'days_hours',
        'description',
        'is_active',
    ];

    protected $casts = [
        'days_hours' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::saved(fn () => Cache::tags(['schedules'])->flush());
        static::deleted(fn () => Cache::tags(['schedules'])->flush());
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
