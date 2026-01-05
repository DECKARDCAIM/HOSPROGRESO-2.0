<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CivilStatus;

class CivilStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Soltero(a)', 'is_active' => true],
            ['name' => 'Casado(a)', 'is_active' => true],
            ['name' => 'Divorciado(a)', 'is_active' => true],
            ['name' => 'Viudo(a)', 'is_active' => true],
            ['name' => 'Unión Libre', 'is_active' => true],
        ];

        foreach ($statuses as $status) {
            CivilStatus::create($status);
        }
    }
}