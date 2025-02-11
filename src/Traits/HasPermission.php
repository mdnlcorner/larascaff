<?php

namespace Mulaidarinull\Larascaff\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

trait HasPermission
{
    protected $abilities = [
        'show' => 'read',
        'index' => 'read',
        'edit' => 'update',
        'update' => 'update',
        'create' => 'create',
        'store' => 'create',
        'destroy' => 'delete',
    ];

    public function callAction($method, $parameters)
    {
        $action = Arr::get($this->abilities, $method);
        if (!$action) {
            return $this->{$method}(...array_values($parameters));
        }
        $staticPath = request()->route()->getCompiled()->getStaticPrefix();

        $urlMenu = urlMenu();
        $staticPath = substr($staticPath, 1);

        if (!in_array($staticPath, $urlMenu)) {
            foreach (array_reverse(explode('/', $staticPath)) as $path) {
                $staticPath = str_replace("/$path", "", $staticPath);
                if (in_array($staticPath, $urlMenu)) {
                    break;
                }
            }
        }

        if (in_array($staticPath, $urlMenu)) {
            Gate::authorize("$action $staticPath");
        }
        return $this->{$method}(...array_values($parameters));
    }
}