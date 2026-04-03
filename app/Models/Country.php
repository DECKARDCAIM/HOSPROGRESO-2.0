<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Country extends Model
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
        static::saved(fn () => Cache::tags(['countries'])->flush());
        static::deleted(fn () => Cache::tags(['countries'])->flush());
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
