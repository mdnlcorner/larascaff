<?php

namespace Mulaidarinull\Larascaff\Concerns;

class ModuleAction
{
    public static function make(string $permission, string $url, ?string $label = null, string $method = 'GET', \Closure|null|bool $show = null, bool $ajax = true, bool $targetBlank = false, ?string $icon = null, ?string $color = null)
    {
        if (is_bool($show)) {
            $show = fn () => $show;
        }
        $moduleActions[$permission] = [
            'url' => $url,
            'label' => $label ?? ucfirst($permission),
            'method' => $method,
            'show' => $show ?? fn () => true,
            'ajax' => $ajax,
            'blank' => $targetBlank ? '_blank' : '',
            'icon' => $icon ?? ($permission == 'update' ? 'tabler-edit' : ($permission == 'view' ? 'tabler-eye' : ($permission == 'delete' ? 'tabler-trash' : null))),
            'color' => $color ?? ($permission == 'update' ? 'warning' : ($permission == 'delete' ? 'danger' : null)),
        ];

        return $moduleActions;
    }
}
