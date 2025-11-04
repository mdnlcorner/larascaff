<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create([
            'name' => 'administrator',
            'email' => 'administrator@example.com',
        ]);
        $user->assignRole('administrator');
        User::factory(50)->create();
    }
}
