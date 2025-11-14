<?php

namespace Mulaidarinull\Larascaff\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Mulaidarinull\Larascaff\Models\Configuration\Role;

class LarascaffSeeder extends Seeder
{
    // public function __invoke(array $parameters = [])
    // {
    //     dd('invoke');
    // }
    public function run(): void
    {
        // Roles
        Role::create(['name' => 'administrator']);
        Role::create(['name' => 'ceo']);

        // Menus
        File::ensureDirectoryExists(app_path('Larascaff/Pages'));
        foreach (File::allFiles(app_path('Larascaff/Pages')) as $pages) {
            $module = getFileNamespace($pages->getContents()).'\\'.$pages->getFilenameWithoutExtension();
            if ($module) {
                $module::makeMenu();
            }
        }

        File::ensureDirectoryExists(app_path('Larascaff/Modules'));
        foreach (File::allFiles(app_path('Larascaff/Modules')) as $modules) {
            $module = getFileNamespace($modules->getContents()).'\\'.$modules->getFilenameWithoutExtension();
            if ($module) {
                $module::makeMenu();
            }
        }

        // Users
        $user = User::factory()->create([
            'name' => 'administrator',
            'email' => 'administrator@example.com',
        ]);

        if (method_exists($user, 'assignRole')) {
            $user->assignRole('administrator');
        }

        Cache::forget('menus');
        Cache::forget('urlMenu');
    }
}
