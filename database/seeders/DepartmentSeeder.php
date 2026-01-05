<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $guatemala = Country::where('name', 'Guatemala')->first();
        
        if (!$guatemala) {
            $this->command->error('País Guatemala no encontrado. Ejecute primero CountrySeeder.');
            return;
        }

        $departments = [
            ['name' => 'Alta Verapaz'],
            ['name' => 'Baja Verapaz'],
            ['name' => 'Chimaltenango'],
            ['name' => 'Chiquimula'],
            ['name' => 'El Progreso'],
            ['name' => 'Escuintla'],
            ['name' => 'Guatemala'],
            ['name' => 'Huehuetenango'],
            ['name' => 'Izabal'],
            ['name' => 'Jalapa'],
            ['name' => 'Jutiapa'],
            ['name' => 'Petén'],
            ['name' => 'Quetzaltenango'],
            ['name' => 'Quiché'],
            ['name' => 'Retalhuleu'],
            ['name' => 'Sacatepéquez'],
            ['name' => 'San Marcos'],
            ['name' => 'Santa Rosa'],
            ['name' => 'Sololá'],
            ['name' => 'Suchitepéquez'],
            ['name' => 'Totonicapán'],
            ['name' => 'Zacapa'],
        ];

        foreach ($departments as $dept) {
            Department::create([
                'country_id' => $guatemala->id,
                'name' => $dept['name'],
                'is_active' => true,
            ]);
        }
    }
}