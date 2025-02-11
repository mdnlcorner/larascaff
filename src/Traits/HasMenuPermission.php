<?php

namespace Mulaidarinull\Larascaff\Traits;

use Mulaidarinull\Larascaff\Models\Configuration\Permission;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;

trait HasMenuPermission
{
    public function attachMenupermission(Menu $menu, array | null $permissions, array | null $roles)
    {
        if (is_null($permissions)) {
            $permissions = ['create', 'read', 'update', 'delete'];
        };

        foreach ($permissions as $item) {
            $permission = Permission::create(['name' => $item . " {$menu->url}", 'menu_id' => $menu->id]);
            if ($roles) {
                $permission->assignRole($roles);
            }
        }
    }
}
