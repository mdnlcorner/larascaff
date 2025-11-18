<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Mulaidarinull\Larascaff\Traits\HasMenuPermission;

class MenuSeeder extends Seeder
{
    use HasMenuPermission;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cache::forget('menus');
        Cache::forget('urlMenu');

        File::ensureDirectoryExists(app_path('Larascaff/Pages'));

        foreach (File::allFiles(app_path('Larascaff/Pages')) as $pages) {
            $module = getFileNamespace($pages->getContents()) . '\\' . $pages->getFilenameWithoutExtension();
            if ($module) {
                $module::makeMenu();
            }
        }

        File::ensureDirectoryExists(app_path('Larascaff/Modules'));
        foreach (File::allFiles(app_path('Larascaff/Modules')) as $modules) {
            $module = getFileNamespace($modules->getContents()) . '\\' . $modules->getFilenameWithoutExtension();
            if ($module) {
                $module::makeMenu();
            }
        }
    }
}
