<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];

    /**
     * The patients that have this allergy.
     */
    public function patients()
    {
        return $this->belongsToMany(Patient::class);
    }
}
