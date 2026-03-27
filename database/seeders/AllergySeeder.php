<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Allergy;

class AllergySeeder extends Seeder
{
    public function run(): void
    {
        $allergies = [
            ['name' => 'Penicilina', 'is_active' => true],
            ['name' => 'Amoxicilina', 'is_active' => true],
            ['name' => 'Ampicilina', 'is_active' => true],
            ['name' => 'Sulfonamidas', 'is_active' => true],
            ['name' => 'Aspirina (AAS)', 'is_active' => true],
            ['name' => 'Ibuprofeno', 'is_active' => true],
            ['name' => 'Naproxeno', 'is_active' => true],
            ['name' => 'Diclofenaco', 'is_active' => true],
            ['name' => 'Metamizol (Dipirona)', 'is_active' => true],
            ['name' => 'Codeína', 'is_active' => true],
            ['name' => 'Morfina', 'is_active' => true],
            ['name' => 'Látex', 'is_active' => true],
            ['name' => 'Yodo / Contraste yodado', 'is_active' => true],
            ['name' => 'Polen', 'is_active' => true],
            ['name' => 'Ácaros del polvo', 'is_active' => true],
            ['name' => 'Mariscos', 'is_active' => true],
            ['name' => 'Maní / Cacahuete', 'is_active' => true],
            ['name' => 'Huevo', 'is_active' => true],
            ['name' => 'Leche / Lácteos', 'is_active' => true],
            ['name' => 'Gluten / Trigo', 'is_active' => true],
            ['name' => 'Soja', 'is_active' => true],
            ['name' => 'Frutos secos', 'is_active' => true],
            ['name' => 'Picadura de abeja / avispa', 'is_active' => true],
            ['name' => 'Metronidazol', 'is_active' => true],
            ['name' => 'Otro', 'is_active' => true],
        ];

        foreach ($allergies as $allergy) {
            Allergy::create($allergy);
        }
    }
}
