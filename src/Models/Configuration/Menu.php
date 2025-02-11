<?php

namespace Mulaidarinull\Larascaff\Models\Configuration;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'url', 'category', 'orders', 'main_menu_id', 'icon', 'active'];

    public function __construct()
    {
        $this->attributes['orders'] = 0;
    }

    public function scopeActive(Builder $query)
    {
        $query->where('active', 1);
    }

    public function subMenus()
    {
        return $this->hasMany(Menu::class, 'main_menu_id');
    }

    public function mainMenu()
    {
        return $this->belongsTo(Menu::class, 'main_menu_id');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'menu_id', 'id');
    }

    public function getMenus()
    {
        return $this->with(['subMenus' => function ($query) {
            $query->active()->orderBy('orders');
        }, 'subMenus.subMenus' => function ($query) {
            $query->active()->orderBy('orders');
        }])->whereNull('main_menu_id')
            ->orderBy('orders')
            ->active()
            ->get();
    }
}
