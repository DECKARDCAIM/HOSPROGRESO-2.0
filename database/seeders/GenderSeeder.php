<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gender;

class GenderSeeder extends Seeder
{
    public function run(): void
    {
        $genders = [
            ['name' => 'Masculino', 'is_active' => true],
            ['name' => 'Femenino', 'is_active' => true],
            ['name' => 'Otro', 'is_active' => true],
        ];

        foreach ($genders as $gender) {
            Gender::create($gender);
        }
    }
}