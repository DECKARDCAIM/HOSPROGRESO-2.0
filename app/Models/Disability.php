<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disability extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];

    /**
     * The patients that have this disability.
     */
    public function patients()
    {
        return $this->belongsToMany(Patient::class);
    }
}
