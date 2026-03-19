<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        Country::create([
            'name' => 'Guatemala',
            'description' => 'País de Centroamérica, con una rica cultura y tradiciones.',
            'is_active' => true,
        ]);
    }
}