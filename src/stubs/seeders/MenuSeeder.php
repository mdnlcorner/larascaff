<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
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

        $mm = Menu::firstOrCreate(['url' => (getPrefix() ? getPrefix() . '/' : '') . 'dashboard'], ['name' => 'Dashboard', 'category' => '', 'icon' => 'tabler-home']);
        $this->attachMenupermission($mm, ['read'], ['ADMINISTRATOR']);

        File::ensureDirectoryExists(app_path('Larascaff/Modules'));
        foreach (File::allFiles(app_path('Larascaff/Modules')) as $modules) {
            $module = getFileNamespace($modules->getContents()) . '\\' . $modules->getFilenameWithoutExtension();
            if ($module) {
                $module::makeMenu();
            }
        }
    }
}
