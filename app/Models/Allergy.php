<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Allergy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected static function booted()
    {
        static::saved(fn () => Cache::tags(['allergies'])->flush());
        static::deleted(fn () => Cache::tags(['allergies'])->flush());
    }

    public function patients()
    {
        return $this->belongsToMany(Patient::class);
    }
}
