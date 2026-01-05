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
            'name' => 'Admin User',
            'email' => 'falla3235@hotmail.com',
            'password' => bcrypt('CAllofduty123@%'),
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