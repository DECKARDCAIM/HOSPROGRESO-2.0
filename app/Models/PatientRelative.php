<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'dpi',
    ];

    /**
     * Get the patient that owns the relative.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the relationship type.
     */
    public function relationshipType()
    {
        return $this->belongsTo(RelationshipType::class);
    }
}
