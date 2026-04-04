<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Gender extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::saved(fn () => Cache::tags(['genders'])->flush());
        static::deleted(fn () => Cache::tags(['genders'])->flush());
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
