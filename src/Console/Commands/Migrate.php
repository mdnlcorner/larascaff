<?php

namespace Mulaidarinull\Larascaff\Console\Commands;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;

class Migrate extends BaseCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larascaff:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate tables for larascaff';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Roles
        Role::create(['name' => 'administrator']);
        Role::create(['name' => 'ceo']);

        // Menus
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

        // Users
        $user = User::factory()->create([
            'name' => 'administrator',
            'email' => 'administrator@example.com',
        ]);

        if (method_exists($user, 'assignRole')) {
            $user->assignRole('administrator');
        }
        
        User::factory(50)->create();
    }
}
