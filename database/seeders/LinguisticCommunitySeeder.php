<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LinguisticCommunity;

class LinguisticCommunitySeeder extends Seeder
{
    public function run(): void
    {
        $communities = [
            ['name' => 'Ingles', 'is_active' => true],
            ['name' => 'Español', 'is_active' => true],
            ['name' => 'Portugues', 'is_active' => true],
        ];

        foreach ($communities as $community) {
            LinguisticCommunity::create($community);
        }
    }
}