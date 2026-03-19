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
        ]);

        $roleId = \App\Models\Role::where('name', 'Administrador')->first()->id ?? null;
        $unityId = \App\Models\UnityExecution::where('code', '234')->first()->id ?? null;
        $departmentId = \App\Models\WorkDepartment::where('name', 'Informática')->first()->id ?? null;

        User::create([
            'first_name' => 'Cristoffer',
            'second_name' => 'Alexis',
            'first_last_name' => 'Falla',
            'second_last_name' => 'Marroquin',
            'married_last_name' => null,
            'email' => 'falla3235@hotmail.com',
            'password' => bcrypt('CAllofduty123@%'),
            'is_active' => true,
            'role_id' => $roleId,
            'unity_execution_id' => $unityId,
            'work_department_id' => $departmentId,
            'profile_photo_path' => null,
            'banner_photo_path' => null,
            'cui' => null,
            'nit' => null,
            'marital_status' => null,
            'phone' => null,
            'address' => null,
            'birth_date' => null,
            'gender' => null,
            'estado' => 'disponible',
            'theme_preference' => 'auto',
        ]);

        User::create([
            'first_name' => 'Test',
            'second_name' => null,
            'first_last_name' => 'Informatica',
            'second_last_name' => null,
            'married_last_name' => null,
            'email' => 'test@gmail.com',
            'password' => bcrypt('1234567890'),
            'is_active' => true,
            'role_id' => $roleId,
            'unity_execution_id' => $unityId,
            'work_department_id' => $departmentId,
            'profile_photo_path' => null,
            'banner_photo_path' => null,
            'cui' => null,
            'nit' => null,
            'marital_status' => null,
            'phone' => null,
            'address' => null,
            'birth_date' => null,
            'gender' => null,
            'estado' => 'disponible',
            'theme_preference' => 'auto',
        ]);
    }
}