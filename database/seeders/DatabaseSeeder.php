<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UnityExecutionSeeder::class,
            WorkDepartmentSeeder::class,
            CountrySeeder::class,
            GenderSeeder::class,
            CivilStatusSeeder::class,
            EthnicitySeeder::class,
            LinguisticCommunitySeeder::class,
            DepartmentSeeder::class,
            MunicipalitySeeder::class,
            RelationshipTypeSeeder::class,
            ScheduleSeeder::class,
            SpecialtySeeder::class,
            AllergySeeder::class,
            DisabilitySeeder::class,
        ]);

        $roleId = \App\Models\Role::where('name', 'Administrador')->first()->id ?? null;
        $unityId = \App\Models\UnityExecution::where('code', '234')->first()->id ?? null;
        $departmentId = \App\Models\WorkDepartment::where('name', 'Informática')->first()->id ?? null;

        $municipalityId = \App\Models\Municipality::where('name', 'Guastatoya')->first()->id ?? null;
        $genderId = \App\Models\Gender::where('name', 'Masculino')->first()->id ?? 1;
        $civilStatusId = \App\Models\CivilStatus::where('name', 'Soltero')->first()->id ?? 1;

        $cristoffer = User::create([
            'first_name' => 'Cristoffer',
            'second_name' => 'Alexis',
            'first_last_name' => 'Falla',
            'second_last_name' => 'Marroquin',
            'married_last_name' => null,
            'email' => 'falla3235@hotmail.com',
            'password' => bcrypt('CAllofduty123@%'),
            'is_active' => true,
            'role_id' => $roleId,
            'profile_photo_path' => null,
            'banner_photo_path' => null,
            'theme_preference' => 'auto',
        ]);

        $cristoffer->staff()->create([
            'unity_execution_id' => $unityId,
            'work_department_id' => $departmentId,
            'cui' => '1234567890123',
            'nit' => '1234567-8',
            'civil_status_id' => $civilStatusId,
            'phone' => '12345678',
            'address' => 'Ciudad de Guatemala',
            'birth_date' => '1990-01-01',
            'gender_id' => $genderId,
            'municipality_id' => $municipalityId,
        ]);

        $test = User::create([
            'first_name' => 'Test',
            'second_name' => null,
            'first_last_name' => 'Informatica',
            'second_last_name' => null,
            'married_last_name' => null,
            'email' => 'test@gmail.com',
            'password' => bcrypt('1234567890'),
            'is_active' => true,
            'role_id' => $roleId,
            'profile_photo_path' => null,
            'banner_photo_path' => null,
            'theme_preference' => 'auto',
        ]);

        $test->staff()->create([
            'unity_execution_id' => $unityId,
            'work_department_id' => $departmentId,
            'cui' => '9876543210987',
            'nit' => '9876543-2',
            'civil_status_id' => $civilStatusId,
            'phone' => '87654321',
            'address' => 'Guastatoya, El Progreso',
            'birth_date' => '1995-05-05',
            'gender_id' => $genderId,
            'municipality_id' => $municipalityId,
        ]);
    }
}