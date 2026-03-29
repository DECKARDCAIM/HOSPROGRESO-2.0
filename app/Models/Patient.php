<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'second_name',
        'third_name',
        'first_last_name',
        'second_last_name',
        'married_last_name',
        'email',
        'phone',
        'dpi',
        'birth_date',
        'gender_id',
        'civil_status_id',
        'ethnicity_id',
        'linguistic_community_id',
        'education',
        'occupation',
        'country_id',
        'department_id',
        'municipality_id',
        'place',
        // Datos de la madre (para menores de edad)
        'mother_first_name',
        'mother_second_name',
        'mother_third_name',
        'mother_first_last_name',
        'mother_second_last_name',
        'mother_married_last_name',
        'mother_dpi',
    ];

    /**
     * Get the clinical record associated with the patient.
     */
    public function clinicalRecord()
    {
        return $this->hasOne(ClinicalRecord::class);
    }

    /**
     * Get the relatives for the patient.
     */
    public function relatives()
    {
        return $this->hasMany(PatientRelative::class);
    }

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function civilStatus()
    {
        return $this->belongsTo(CivilStatus::class);
    }

    public function ethnicity()
    {
        return $this->belongsTo(Ethnicity::class);
    }

    public function linguisticCommunity()
    {
        return $this->belongsTo(LinguisticCommunity::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    /**
     * The allergies associated with the patient.
     */
    public function allergies()
    {
        return $this->belongsToMany(Allergy::class);
    }

    /**
     * The disabilities associated with the patient.
     */
    public function disabilities()
    {
        return $this->belongsToMany(Disability::class);
    }

    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }
        
        return $this->birth_date->age;
    }

    public function isMinor(): bool
    {
        return $this->age !== null && $this->age < 18;
    }


    public function getFullNameAttribute()
    {
        if (!$this->first_name && $this->mother_full_name) {
            return "Hijo de {$this->mother_full_name}";
        }
        
        $names = array_filter([
            $this->first_name,
            $this->second_name,
            $this->third_name,
        ]);
        
        $lastNames = array_filter([
            $this->first_last_name,
            $this->second_last_name,
        ]);
        
        $marriedLastName = $this->married_last_name ? " de {$this->married_last_name}" : '';
        
        return trim(implode(' ', $names) . ' ' . implode(' ', $lastNames) . $marriedLastName);
    }

    public function getOnlyNamesAttribute()
    {
        if (!$this->first_name && $this->mother_full_name) {
            return "Hijo de {$this->mother_first_name} {$this->mother_second_name} {$this->mother_third_name}";
        }

        $names = array_filter([
            $this->first_name,
            $this->second_name,
            $this->third_name,
        ]);

        return trim(implode(' ', $names));
    }

    public function getOnlyLastNamesAttribute()
    {
        if (!$this->first_name && $this->mother_full_name) {
            $lastNames = array_filter([
                $this->mother_first_last_name,
                $this->mother_second_last_name,
            ]);
            $married = $this->mother_married_last_name ? " de {$this->mother_married_last_name}" : '';
            return trim(implode(' ', $lastNames) . $married);
        }

        $lastNames = array_filter([
            $this->first_last_name,
            $this->second_last_name,
        ]);

        $marriedLastName = $this->married_last_name ? " de {$this->married_last_name}" : '';

        return trim(implode(' ', $lastNames) . $marriedLastName);
    }

    public function getMotherFullNameAttribute()
    {
        if (!$this->mother_first_name) {
            return null;
        }
        
        $names = array_filter([
            $this->mother_first_name,
            $this->mother_second_name,
            $this->mother_third_name,
        ]);
        
        $lastNames = array_filter([
            $this->mother_first_last_name,
            $this->mother_second_last_name,
        ]);
        
        $marriedLastName = $this->mother_married_last_name ? " de {$this->mother_married_last_name}" : '';
        
        return trim(implode(' ', $names) . ' ' . implode(' ', $lastNames) . $marriedLastName);
    }

    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
            ->orWhere('second_name', 'like', "%{$search}%")
            ->orWhere('third_name', 'like', "%{$search}%")
            ->orWhere('first_last_name', 'like', "%{$search}%")
            ->orWhere('second_last_name', 'like', "%{$search}%")
            ->orWhere('dpi', 'like', "%{$search}%")
            ->orWhere('mother_first_name', 'like', "%{$search}%")
            ->orWhere('mother_second_name', 'like', "%{$search}%")
            ->orWhere('mother_third_name', 'like', "%{$search}%")
            ->orWhere('mother_first_last_name', 'like', "%{$search}%")
            ->orWhere('mother_second_last_name', 'like', "%{$search}%")
            ->orWhere('mother_dpi', 'like', "%{$search}%");
        });
    }


    public function scopeByGender($query, $genderId)
    {
        return $query->where('gender_id', $genderId);
    }

    public function scopeByCivilStatus($query, $civilStatusId)
    {
        return $query->where('civil_status_id', $civilStatusId);
    }

    public function scopeByEthnicity($query, $ethnicityId)
    {
        return $query->where('ethnicity_id', $ethnicityId);
    }

    public function scopeByLinguisticCommunity($query, $linguisticCommunityId)
    {
        return $query->where('linguistic_community_id', $linguisticCommunityId);
    }

    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeByMunicipality($query, $municipalityId)
    {
        return $query->where('municipality_id', $municipalityId);
    }
}