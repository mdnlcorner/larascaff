<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
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

        $prefix = getPrefix();
        if ($prefix != '') $prefix .= '/';
        
        $mm = Menu::firstOrCreate(['url' => $prefix. 'dashboard'],['name' => 'Dashboard', 'category' => '', 'icon' => 'tabler-home']);
        $this->attachMenupermission($mm, ['read'], ['ADMINISTRATOR']);
        
        $mm = Menu::firstOrCreate(['url' => $prefix. 'users'],['name' => 'Users', 'category' => 'CONFIGURATION', 'icon' => 'tabler-users-group']);
        $this->attachMenupermission($mm, ['create', 'read', 'update', 'update-permissions'], ['ADMINISTRATOR']);

        $mm = Menu::firstOrCreate(['url' => $prefix. 'configuration'],['name' => 'Configuration', 'category' => 'CONFIGURATION', 'icon' => 'tabler-settings']);
        $this->attachMenupermission($mm, ['read'], ['ADMINISTRATOR']);
        
        $sm = $mm->subMenus()->create(['name' => 'Menus', 'url' => $mm->url. '/menus', 'category' => $mm->category]);
        $this->attachMenupermission($sm, ['create', 'read' , 'update', 'delete', 'sort'], ['ADMINISTRATOR']);
        
        $sm = $mm->subMenus()->create(['name' => 'Roles', 'url' => $mm->url. '/roles', 'category' => $mm->category]);
        $this->attachMenupermission($sm, ['create', 'read' , 'update', 'update-permissions'], ['ADMINISTRATOR']);
        
        $sm = $mm->subMenus()->create(['name' => 'Permissions', 'url' => $mm->url. '/permissions', 'category' => $mm->category]);
        $this->attachMenupermission($sm, null, ['ADMINISTRATOR']);
    }
}
