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
            ['name' => 'Achi', 'is_active' => true],
            ['name' => 'Akateko', 'is_active' => true],
            ['name' => 'Awakateko', 'is_active' => true],
            ['name' => 'Chalchiteko', 'is_active' => true],
            ['name' => 'Ch\'orti\'', 'is_active' => true],
            ['name' => 'Chuj', 'is_active' => true],
            ['name' => 'Itza', 'is_active' => true],
            ['name' => 'Ixil', 'is_active' => true],
            ['name' => 'Jakalteko', 'is_active' => true],
            ['name' => 'Kaqchikel', 'is_active' => true],
            ['name' => 'K\'iche\'', 'is_active' => true],
            ['name' => 'Mam', 'is_active' => true],
            ['name' => 'Mopan', 'is_active' => true],
            ['name' => 'Poqomam', 'is_active' => true],
            ['name' => 'Poqomchi\'', 'is_active' => true],
            ['name' => 'Q\'anjob\'al', 'is_active' => true],
            ['name' => 'Q\'eqchi\'', 'is_active' => true],
            ['name' => 'Sakapulteko', 'is_active' => true],
            ['name' => 'Tektiteko', 'is_active' => true],
            ['name' => 'Tz\'utujil', 'is_active' => true],
            ['name' => 'Uspanteko', 'is_active' => true],
            ['name' => 'Xinka', 'is_active' => true],
            ['name' => 'Español', 'is_active' => true],
            ['name' => 'Garífuna', 'is_active' => true],
        ];

        foreach ($communities as $community) {
            LinguisticCommunity::create($community);
        }
    }
}