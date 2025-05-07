<?php

namespace Mulaidarinull\Larascaff\Actions;

use Illuminate\Http\Request;
use Mulaidarinull\Larascaff\Enums\ColorVariant;

class Action
{
    public static function make(string $permission, string $url, ?string $label = null, ?string $method = 'GET', \Closure | null | bool $show = null, bool $ajax = true, bool $targetBlank = false, ?string $icon = null, string | ColorVariant | null $color = null)
    {
        if (is_bool($show)) {
            $show = fn () => $show;
        }

        if ($color instanceof ColorVariant) {
            $color = $color->value;
        }

        $actions[$permission] = [
            'permission' => $permission,
            'url' => $url,
            'label' => $label ?? ucfirst($permission),
            'method' => $method,
            'show' => $show ?? fn () => true,
            'ajax' => $ajax,
            'blank' => $targetBlank ? '_blank' : '',
            'icon' => $icon ?? ($permission == 'update' ? 'tabler-edit' : ($permission == 'read' ? 'tabler-eye' : ($permission == 'delete' ? 'tabler-trash' : null))),
            'color' => $color ?? ($permission == 'update' ? 'warning' : ($permission == 'delete' ? 'danger' : null)),
        ];

        return $actions;
    }

    public function actionHandler(Request $request)
    {
        if ($request->ajax()) {
            $request->validate(['module' => 'required', 'method' => 'required']);
            if (! class_exists($request->module)) {
                return responseError('Class not exist');
            }

            return $request->module::{$request->method}($request);
        }
        abort(404);
    }
}
