<?php

namespace Mulaidarinull\Larascaff\Traits;

use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Mulaidarinull\Larascaff\Models\Configuration\Permission;
use Mulaidarinull\Larascaff\Pages\Page;

trait HasMenuPermission
{
    protected static array $permissions = ['create', 'read', 'update', 'delete'];

    protected static ?string $menuIcon = 'tabler-circle';

    protected static ?string $menuCategory = '';

    public static function makeMenuHandler()
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
        static::attachMenupermission($mm, count($subMenus) ? ['read'] : static::getPermissions(), ['administrator']);

        foreach ($subMenus as $key => $sub) {
            $mm = $mm->subMenus()->firstOrCreate(['url' => $mm->url.'/'.$sub], ['name' => ucwords(str_replace('-', ' ', $sub)), 'url' => $mm->url.'/'.$sub, 'category' => $mm->category]);
            static::attachMenupermission($mm, (count($subMenus) > 1 && $key == 0) ? ['read'] : static::getPermissions(), ['administrator']);
        }
    }

    public static function getPermissions()
    {
        if (method_exists(static::class, 'permissions') && is_subclass_of(static::class, Page::class)) {
            return call_user_func([static::class, 'permissions']);
        }

        return array_unique([
            ...static::$permissions,
            ...static::getActions()->map(fn ($item) => $item['permission'])->toArray(),
            ...static::getTableActions()->map(fn ($item) => $item['permission'])->toArray(),
        ]);
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
