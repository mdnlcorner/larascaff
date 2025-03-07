<?php

namespace Mulaidarinull\Larascaff\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Mulaidarinull\Larascaff\Traits\HasMenuPermission;

class BaseCommand extends Command
{
    use HasMenuPermission;

    /**
     * Filesystem instance
     *
     * @var Filesystem
     */
    protected $fileSystem;

    protected $pathList;

    protected $path;

    public function __construct(Filesystem $fileSystem)
    {
        parent::__construct();
        $this->fileSystem = $fileSystem;
    }

    public function makeMenu($name, $permissions = null)
    {
        $url = getPrefix();
        // has main menu
        if (count($this->pathList)) {
            for ($i = 0; $i < count($this->pathList); $i++) {
                $url .= ($url ? '/' : '').Str::kebab($this->pathList[$i]);
                $mainMenu = Menu::query()->where('url', $url)->first();
                if (! $mainMenu) {
                    $mainMenu = Menu::query()->create([
                        'url' => $url,
                        'icon' => 'tabler-circle',
                        'name' => ucwords(str_replace('-', ' ', Str::kebab($this->pathList[$i]))),
                    ]);
                    $this->attachMenupermission($mainMenu, ['read'], ['ADMINISTRATOR']);
                }

                $urlSubmenu = $url.'/'.Pluralizer::plural(Str::kebab($this->pathList[$i + 1] ?? $name));

                $isExist = $mainMenu->subMenus()->where('url', $urlSubmenu)->first();
                if (! $isExist) {
                    $sm = $mainMenu->subMenus()->create([
                        'name' => ucwords(str_replace('-', ' ', Str::kebab($this->pathList[$i + 1] ?? $name))),
                        'url' => $urlSubmenu,
                    ]);
                    $this->attachMenupermission($sm, isset($this->pathList[$i + 1]) ? ['read'] : $permissions, ['ADMINISTRATOR']);
                }
            }
        } else {
            $url .= ($url ? '/' : '').Str::kebab($name);
            $isExist = Menu::query()->where('url', $url)->first();
            if (! $isExist) {
                $menu = Menu::query()->create([
                    'url' => $url,
                    'icon' => 'tabler-circle',
                    'name' => ucwords(str_replace('-', ' ', Str::kebab($name))),
                ]);
                $this->attachMenupermission($menu, $permissions, ['ADMINISTRATOR']);
            }
        }

        Cache::forget('menus');
        Cache::forget('urlMenu');
    }

    protected function saveStub($stub, $replaces, $outputFilePath, $type = null)
    {
        $stub = file_get_contents($stub);
        foreach ($replaces as $key => $value) {
            $stub = str_replace($key, $value, $stub);
        }
        file_put_contents($outputFilePath, $stub);
        if ($type) {
            $this->components->info(sprintf('%s [%s] created successfully.', $type, $outputFilePath));
        }
    }

    protected function resolveStubPath($stub)
    {
        return $this->fileSystem->exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    protected function makeDirectory($path)
    {
        if (! $this->fileSystem->isDirectory($path)) {
            $this->fileSystem->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }
}
