<?php

namespace Mulaidarinull\Larascaff\Traits;

use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Mulaidarinull\Larascaff\Models\Configuration\Permission;

trait HasMenuPermission
{
    protected array $permissions = ['create' => true, 'read' => true, 'update' => true, 'delete' => true];

    protected string $menuIcon = 'tabler-circle';

    protected string $menuCategory = '';

    public function handleMakeMenu()
    {
        $prefix = getPrefix();

        $menus = explode('/', $this->url);
        $subMenus = [];

        foreach ($menus as $key => $value) {
            if ($key < ($prefix ? 2 : 1)) {
                $mainMenu[] = $value;
            } else {
                $subMenus[] = $value;
            }
        }

        $mm = Menu::firstOrCreate(['url' => implode('/', $mainMenu)], ['name' => ucwords(str_replace('-', ' ', array_pop($mainMenu))), 'category' => $this->menuCategory, 'icon' => $this->menuIcon]);
        $this->attachMenupermission($mm, count($subMenus) ? ['read'] : $this->getPermissions(), ['ADMINISTRATOR']);

        foreach ($subMenus as $key => $sub) {
            $mm = $mm->subMenus()->firstOrCreate(['url' => $mm->url.'/'.$sub], ['name' => ucwords(str_replace('-', ' ', $sub)), 'url' => $mm->url.'/'.$sub, 'category' => $mm->category]);
            $this->attachMenupermission($mm, (count($subMenus) > 1 && $key == 0) ? ['read'] : $this->getPermissions(), ['ADMINISTRATOR']);
        }
    }

    public function getPermissions()
    {
        return array_keys($this->permissions);
    }

    public function attachMenupermission(Menu $menu, ?array $permissions, ?array $roles)
    {
        if (is_null($permissions)) {
            $permissions = ['create', 'read', 'update', 'delete'];
        }

        foreach ($permissions as $item) {
            $permission = Permission::firstOrCreate(['name' => $item." {$menu->url}"], ['name' => $item." {$menu->url}", 'menu_id' => $menu->id]);
            if ($roles) {
                $permission->assignRole($roles);
            }
        }
    }
}
