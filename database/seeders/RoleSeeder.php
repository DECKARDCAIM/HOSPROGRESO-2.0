<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'id'        => 1,
            'name'      => 'Administrador',
            'is_active' => true,
        ]);
    }
}
