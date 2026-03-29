<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'second_name',
        'third_name',
        'first_last_name',
        'second_last_name',
        'married_last_name',

        'email',
        'password',
        'role_id',
        'is_active',
        
        'profile_photo_path',
        'banner_photo_path',
        'cui',
        'nit',
        'marital_status',
        'phone',
        'unity_execution_id',
        'work_department_id',
        'collegiate_number',
        'specialty_id',
        'address',
        'birth_date',
        'gender_id',
        'estado',
        'theme_preference',
        'country_id',
        'department_id',
        'municipality_id',
        'schedule_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function unityExecution()
    {
        return $this->belongsTo(UnityExecution::class);
    }

    public function workDepartment()
    {
        return $this->belongsTo(WorkDepartment::class);
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

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the avatar URL attribute - siempre genera URL correcta basada en la solicitud actual
     *
     * @return string|null
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }
        
        return null;
    }

    /**
     * Get the banner URL attribute - siempre genera URL correcta basada en la solicitud actual
     *
     * @return string|null
     */
    public function getBannerUrlAttribute()
    {
        if ($this->banner_photo_path) {
            return asset('storage/' . $this->banner_photo_path);
        }
        
        return null;
    }

    /**
     * Genera URL de imagen basada en la solicitud actual (funciona con dominio e IP)
     *
     * @param string $path
     * @return string
     */
    private function getImageUrl($path)
    {
        // Usar asset() que genera URLs relativas al dominio actual
        // El JavaScript se encargará de ajustar las URLs según el hostname
        return asset($path);
    }

    public function readReleases()
    {
        return $this->belongsToMany(Release::class, 'release_user')->withPivot('read_at')->withTimestamps();
    }
}
