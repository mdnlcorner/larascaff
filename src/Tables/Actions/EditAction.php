<?php

namespace Mulaidarinull\Larascaff\Tables\Actions;

use Mulaidarinull\Larascaff\Enums\ColorVariant;

class EditAction extends Action
{
    public static function make(string $permission = 'update', string $url = '/{{id}}/edit', ?string $label = 'Edit', ?string $method = 'GET', \Closure | null | bool $show = null, ?bool $ajax = true, ?bool $targetBlank = false, ?string $icon = null, string | ColorVariant | null $color = null)
    {
        $moduleActions = parent::make($permission, $url, $label, $method, $show, $ajax, $targetBlank, $icon, $color);

        return $moduleActions;
    }
}
