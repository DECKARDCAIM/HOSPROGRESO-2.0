<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ethnicity;

class EthnicitySeeder extends Seeder
{
    public function run(): void
    {
        $ethnicities = [
            ['name' => 'Maya', 'is_active' => true],
            ['name' => 'Garífuna', 'is_active' => true],
            ['name' => 'Xinca', 'is_active' => true],
            ['name' => 'Ladino', 'is_active' => true],
            ['name' => 'Afrodescendiente', 'is_active' => true],
            ['name' => 'Otro', 'is_active' => true],
        ];

        foreach ($ethnicities as $ethnicity) {
            Ethnicity::create($ethnicity);
        }
    }
}