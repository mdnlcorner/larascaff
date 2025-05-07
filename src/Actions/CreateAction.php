<?php

namespace Mulaidarinull\Larascaff\Actions;

use Closure;
use Mulaidarinull\Larascaff\Enums\ColorVariant;

class CreateAction extends Action
{
    
    public static function make(string $permission = 'create', string $url = '/create', ?string $label = 'Create', ?string $method = 'GET', Closure|null|bool $show = null, bool $ajax = true, bool $targetBlank = false, ?string $icon = 'tabler-plus', string | ColorVariant | null $color = null)
    {
        $moduleActions = parent::make($permission, $url, $label, $method, $show, $ajax, $targetBlank, $icon, $color);

        return $moduleActions;
    }
}