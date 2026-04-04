<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Ethnicity extends Model
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
        static::saved(fn () => Cache::tags(['ethnicities'])->flush());
        static::deleted(fn () => Cache::tags(['ethnicities'])->flush());
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
