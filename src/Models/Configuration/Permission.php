<?php

namespace Mulaidarinull\Larascaff\Models\Configuration;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission
{
    use HasFactory;

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }

    public function action(): Attribute
    {
        return Attribute::make(get: function ($value, $attr) {
            return explode(' ', $attr['name'])[0];
        });
    }
}
