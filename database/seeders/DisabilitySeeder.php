<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Disability;

class DisabilitySeeder extends Seeder
{
    public function run(): void
    {
        $disabilities = [
            ['name' => 'Visual (ceguera total)', 'is_active' => true],
            ['name' => 'Visual (baja visión)', 'is_active' => true],
            ['name' => 'Auditiva (sordera total)', 'is_active' => true],
            ['name' => 'Auditiva (hipoacusia)', 'is_active' => true],
            ['name' => 'Motora (miembros superiores)', 'is_active' => true],
            ['name' => 'Motora (miembros inferiores)', 'is_active' => true],
            ['name' => 'Motora (paraplejia)', 'is_active' => true],
            ['name' => 'Motora (tetraplejia)', 'is_active' => true],
            ['name' => 'Motora (hemiplejia)', 'is_active' => true],
            ['name' => 'Intelectual / Cognitiva', 'is_active' => true],
            ['name' => 'Del habla / Comunicación', 'is_active' => true],
            ['name' => 'Psicosocial / Mental', 'is_active' => true],
            ['name' => 'Síndrome de Down', 'is_active' => true],
            ['name' => 'Trastorno del Espectro Autista (TEA)', 'is_active' => true],
            ['name' => 'Parálisis cerebral', 'is_active' => true],
            ['name' => 'Epilepsia', 'is_active' => true],
            ['name' => 'Múltiple', 'is_active' => true],
            ['name' => 'Otra', 'is_active' => true],
        ];

        foreach ($disabilities as $disability) {
            Disability::create($disability);
        }
    }
}
