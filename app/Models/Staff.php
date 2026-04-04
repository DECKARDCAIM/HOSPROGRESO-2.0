<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'user_id',
        'cui',
        'nit',
        'civil_status_id',
        'phone',
        'unity_execution_id',
        'work_department_id',
        'collegiate_number',
        'specialty_id',
        'address',
        'birth_date',
        'gender_id',
        'municipality_id',
        'schedule_id',
    ];

    protected static function booted()
    {
        static::saved(fn () => Cache::tags(['users', 'profiles'])->flush());
        static::deleted(fn () => Cache::tags(['users', 'profiles'])->flush());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unityExecution()
    {
        return $this->belongsTo(UnityExecution::class);
    }

    public function workDepartment()
    {
        return $this->belongsTo(WorkDepartment::class, 'work_department_id');
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

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function civilStatus()
    {
        return $this->belongsTo(CivilStatus::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
}
