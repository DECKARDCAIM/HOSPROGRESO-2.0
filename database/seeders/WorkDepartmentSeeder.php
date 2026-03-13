<?php

namespace Database\Seeders;

use App\Models\WorkDepartment;
use Illuminate\Database\Seeder;

class WorkDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Emergencia',
            'Consulta Externa',
            'Ginecologia',
            'Pediatria',
            'Medicina Hombre',
            'Medicina Mujeres',
            'Informática'
        ];

        foreach ($departments as $department) {
            WorkDepartment::firstOrCreate(['name' => $department]);
        }
    }
}
