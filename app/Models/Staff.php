<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
            'id', // ID on municipalities table
            'id', // ID on departments table
            'municipality_id', // Local key on staff table
            'department_id' // Local key on municipalities table
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
