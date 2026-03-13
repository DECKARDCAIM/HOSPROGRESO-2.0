<?php

namespace Database\Seeders;

use App\Models\UnityExecution;
use Illuminate\Database\Seeder;

class UnityExecutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnityExecution::firstOrCreate([
            'id' => 1
        ], [
            'name' => 'Hospital El Progreso',
            'code' => '234',
            'is_active' => true
        ]);
    }
}
