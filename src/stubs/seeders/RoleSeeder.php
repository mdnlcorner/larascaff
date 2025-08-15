<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Mulaidarinull\Larascaff\Models\Configuration\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'administrator']);
        Role::create(['name' => 'ceo']);
    }
}
