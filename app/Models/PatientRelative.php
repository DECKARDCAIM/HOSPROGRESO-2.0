<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PatientRelative extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'relationship_type_id',
        'first_name',
        'second_name',
        'third_name',
        'first_last_name',
        'second_last_name',
        'married_last_name',
        'cui',
    ];

    protected static function booted()
    {
        static::saved(fn () => Cache::tags(['patient_relatives', 'patients'])->flush());
        static::deleted(fn () => Cache::tags(['patient_relatives', 'patients'])->flush());
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function relationshipType()
    {
        return $this->belongsTo(RelationshipType::class);
    }
}
