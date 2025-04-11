<?php

namespace Mulaidarinull\Larascaff\Tables\Actions;

use Mulaidarinull\Larascaff\Enums\ColorVariant;

class DeleteAction extends Action
{
    public static function make(string $permission = 'delete', string $url = '/{{id}}', ?string $label = 'Delete', ?string $method = 'DELETE', \Closure | null | bool $show = null, ?bool $ajax = true, ?bool $targetBlank = false, ?string $icon = null, string | null | ColorVariant $color = null)
    {
        $moduleActions = parent::make($permission, $url, $label, $method, $show, $ajax, $targetBlank, $icon, $color);

        return $moduleActions;
    }
}
