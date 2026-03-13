<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RelationshipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $relationships = [
            'madre',
            'padre',
            'hermano',
            'hermana',
            'tío',
            'tía',
            'abuelo',
            'abuela',
            'primo',
            'prima',
            'tutor legal',
            'otro'
        ];

        foreach ($relationships as $relationship) {
            \App\Models\RelationshipType::updateOrCreate(['name' => $relationship]);
        }
    }
}
