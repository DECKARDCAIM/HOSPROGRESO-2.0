<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

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
        'cui',
        'birth_date',
        'gender_id',
        'civil_status_id',
        'ethnicity_id',
        'linguistic_community_id',
        'education',
        'occupation',
        'municipality_id',
        'place',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    protected static function booted()
    {
        $flushCache = fn () => Cache::tags(['patients'])->flush();
        static::saved($flushCache);
        static::deleted($flushCache);
        static::restored($flushCache);
    }

    public function clinicalRecord()
    {
        return $this->hasOne(ClinicalRecord::class);
    }

    public function relatives()
    {
        return $this->hasMany(PatientRelative::class);
    }

    public function mother()
    {
        return $this->hasOne(PatientRelative::class)
            ->whereHas('relationshipType', fn ($q) => $q->where('name', 'Madre'));
    }

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

    public function department()
    {
        return $this->hasOneThrough(
            Department::class,
            Municipality::class,
            'id',
            'id',
            'municipality_id',
            'department_id'
        );
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function allergies()
    {
        return $this->belongsToMany(Allergy::class);
    }

    public function disabilities()
    {
        return $this->belongsToMany(Disability::class);
    }

    public function getAgeAttribute()
    {
        return $this->birth_date?->age;
    }

    public function isMinor(): bool
    {
        return $this->age !== null && $this->age < 18;
    }

    public function getFullNameAttribute()
    {
        $motherFullName = $this->mother_full_name;
        if (! $this->first_name && $motherFullName) {
            return "Hijo de {$motherFullName}";
        }

        $names = array_filter([$this->first_name, $this->second_name, $this->third_name]);
        $lastNames = array_filter([$this->first_last_name, $this->second_last_name]);
        $marriedLastName = $this->married_last_name ? " de {$this->married_last_name}" : '';

        return trim(implode(' ', $names).' '.implode(' ', $lastNames).$marriedLastName);
    }

    public function getOnlyNamesAttribute()
    {
        $mother = $this->mother;
        if (! $this->first_name && $mother) {
            return "Hijo de {$mother->first_name} {$mother->second_name} {$mother->third_name}";
        }

        $names = array_filter([$this->first_name, $this->second_name, $this->third_name]);

        return trim(implode(' ', $names));
    }

    public function getOnlyLastNamesAttribute()
    {
        $mother = $this->mother;
        if (! $this->first_name && $mother) {
            $lastNames = array_filter([$mother->first_last_name, $mother->second_last_name]);
            $married = $mother->married_last_name ? " de {$mother->married_last_name}" : '';

            return trim(implode(' ', $lastNames).$married);
        }

        $lastNames = array_filter([$this->first_last_name, $this->second_last_name]);
        $marriedLastName = $this->married_last_name ? " de {$this->married_last_name}" : '';

        return trim(implode(' ', $lastNames).$marriedLastName);
    }

    public function getMotherFullNameAttribute()
    {
        $mother = $this->mother;
        if (! $mother) {
            return null;
        }

        $names = array_filter([$mother->first_name, $mother->second_name, $mother->third_name]);
        $lastNames = array_filter([$mother->first_last_name, $mother->second_last_name]);
        $marriedLastName = $mother->married_last_name ? " de {$mother->married_last_name}" : '';

        return trim(implode(' ', $names).' '.implode(' ', $lastNames).$marriedLastName);
    }

    public function scopeSearch($query, $search)
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('second_name', 'like', "%{$search}%")
                ->orWhere('third_name', 'like', "%{$search}%")
                ->orWhere('first_last_name', 'like', "%{$search}%")
                ->orWhere('second_last_name', 'like', "%{$search}%")
                ->orWhere('cui', 'like', "%{$search}%")
                ->orWhereHas('relatives', function ($sub) use ($search) {
                    $sub->where('first_name', 'like', "%{$search}%")
                        ->orWhere('second_name', 'like', "%{$search}%")
                        ->orWhere('third_name', 'like', "%{$search}%")
                        ->orWhere('first_last_name', 'like', "%{$search}%")
                        ->orWhere('second_last_name', 'like', "%{$search}%")
                        ->orWhere('cui', 'like', "%{$search}%");
                });
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
        return $query->whereHas('municipality.department', fn ($q) => $q->where('country_id', $countryId));
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->whereHas('municipality', fn ($q) => $q->where('department_id', $departmentId));
    }

    public function scopeByMunicipality($query, $municipalityId)
    {
        return $query->where('municipality_id', $municipalityId);
    }
}
