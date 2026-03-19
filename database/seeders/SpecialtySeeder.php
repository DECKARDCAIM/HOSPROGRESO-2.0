<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            ['name' => 'Pediatría'],
            ['name' => 'Ginecología'],
            ['name' => 'Traumatología'],
            ['name' => 'Psicología'],
            ['name' => 'Nutrición'],
            ['name' => 'Cirugía'],
            ['name' => 'Medicina General'],
        ];

        foreach ($specialties as $specialty) {
            Specialty::create($specialty);
        }
    }
}
