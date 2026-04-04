<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class UnityExecution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::saved(fn () => Cache::tags(['unity_executions'])->flush());
        static::deleted(fn () => Cache::tags(['unity_executions'])->flush());
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
