<?php

namespace Mulaidarinull\Larascaff\Traits;

use Mulaidarinull\Larascaff\Models\Configuration\Permission;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;

trait HasMenuPermission
{
    protected array $permissions = ['create' => true, 'read' => true, 'update' => true, 'delete' => true];
    protected string $menuIcon = 'tabler-circle';
    protected string $menuCategory = '';

    public function handleMakeMenu()
    {
        $menus = explode('/', $this->url);
        if (count($menus) > 2) {
            $subMenus = array_pop($menus);
        }
        $mainMenu = implode('/', $menus);

        $mm = Menu::firstOrCreate(['url' => $mainMenu], ['name' => ucwords(str_replace('-', ' ', $menus[count($menus) - 1])), 'category' => $this->menuCategory, 'icon' => $this->menuIcon]);
        $this->attachMenupermission($mm, ['read'], ['ADMINISTRATOR']);

        if (isset($subMenus)) {
            $explodeSub = explode('/', $subMenus);
            if (count($explodeSub) > 1) {
                $mm = $mm->subMenus()->firstOrCreate(['url' => $mm->url . '/' . $explodeSub[0]],['name' => ucwords(str_replace('-', ' ', $explodeSub[0])), 'url' => $mm->url . '/' . $explodeSub[0], 'category' => $mm->category]);
                $this->attachMenupermission($mm, ['read'], ['ADMINISTRATOR']);
            }
            $sm = $mm->subMenus()->firstOrCreate(['url' => $mm->url . '/' . (isset($explodeSub[0]) ? $explodeSub[0] : $subMenus)],['name' => $this->pageTitle, 'url' => $mm->url . '/' . (isset($explodeSub[0]) ? $explodeSub[0] : $subMenus), 'category' => $mm->category]);
            $this->attachMenupermission($sm, $this->getPermissions(), ['ADMINISTRATOR']);
        }
    }

    public function getPermissions()
    {
        return array_keys($this->permissions);
    }

    public function attachMenupermission(Menu $menu, array | null $permissions, array | null $roles)
    {
        if (is_null($permissions)) {
            $permissions = ['create', 'read', 'update', 'delete'];
        };

        foreach ($permissions as $item) {
            $permission = Permission::firstOrCreate(['name' => $item . " {$menu->url}"], ['name' => $item . " {$menu->url}", 'menu_id' => $menu->id]);
            if ($roles) {
                $permission->assignRole($roles);
            }
        }
    }
}
