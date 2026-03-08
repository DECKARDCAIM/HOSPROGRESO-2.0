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
        User::create([
            'first_name' => 'Cristoffer',
            'second_name' => 'Alexis',
            'first_last_name' => 'Falla',
            'second_last_name' => 'Marroquin',
            'married_last_name' => null,
            'email' => 'falla3235@hotmail.com',
            'password' => bcrypt('CAllofduty123@%'),
            'is_active' => true,
            'profile_photo_path' => null,
            'banner_photo_path' => null,
            'cui' => null,
            'nit' => null,
            'marital_status' => null,
            'phone' => null,
            'department' => null,
            'address' => null,
            'birth_date' => null,
            'gender' => null,
            'estado' => 'disponible',
            'theme_preference' => 'auto',
        ]);

        $this->call([
            CountrySeeder::class,
            GenderSeeder::class,
            CivilStatusSeeder::class,
            EthnicitySeeder::class,
            LinguisticCommunitySeeder::class,
            DepartmentSeeder::class,
            MunicipalitySeeder::class,
        ]);
    }
}