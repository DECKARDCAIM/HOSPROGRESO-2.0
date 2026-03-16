<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionHistory extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'login_at',
        'last_active_at',
        'is_active',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'last_active_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
