<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClinicalRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'record_number',
    ];

    /**
     * Get the patient that owns the clinical record.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
