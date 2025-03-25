<?php

namespace Mulaidarinull\Larascaff\Traits;

use Mulaidarinull\Larascaff\BasePage;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Mulaidarinull\Larascaff\Models\Configuration\Permission;

trait HasMenuPermission
{
    protected static array $permissions = ['create', 'read', 'update', 'delete'];

    protected static ?string $menuIcon = 'tabler-circle';

    protected static ?string $menuCategory = '';

    public static function handleMakeMenu()
    {
        $prefix = getPrefix();

        $menus = explode('/', static::getUrl());
        $subMenus = [];

        foreach ($menus as $key => $value) {
            if ($key < ($prefix ? 2 : 1)) {
                $mainMenu[] = $value;
            } else {
                $subMenus[] = $value;
            }
        }

        $mm = Menu::firstOrCreate(['url' => implode('/', $mainMenu)], ['name' => ucwords(str_replace('-', ' ', array_pop($mainMenu))), 'category' => static::$menuCategory, 'icon' => static::$menuIcon]);
        static::attachMenupermission($mm, count($subMenus) ? ['read'] : static::getPermissions(), ['ADMINISTRATOR']);

        foreach ($subMenus as $key => $sub) {
            $mm = $mm->subMenus()->firstOrCreate(['url' => $mm->url.'/'.$sub], ['name' => ucwords(str_replace('-', ' ', $sub)), 'url' => $mm->url.'/'.$sub, 'category' => $mm->category]);
            static::attachMenupermission($mm, (count($subMenus) > 1 && $key == 0) ? ['read'] : static::getPermissions(), ['ADMINISTRATOR']);
        }
    }

    public static function getPermissions()
    {
        if (method_exists(static::class, 'permissions') && is_subclass_of(static::class, BasePage::class)) {
            return call_user_func([static::class, 'permissions']);
        }

        // dd(static::table(new \Mulaidarinull\Larascaff\Datatable\BaseDatatable(static::getInstanceModel(), static::getUrl()))->getActions());

        return array_values(array_unique([...static::$permissions, ...array_keys(static::getActions()), ...array_keys(static::getTableActions())]));
    }

    public static function attachMenupermission(Menu $menu, ?array $permissions, ?array $roles)
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
