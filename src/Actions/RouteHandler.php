<?php

namespace Mulaidarinull\Larascaff\Actions;

use Illuminate\Http\Request;

class RouteHandler
{
    public function __invoke(Request $request)
    {
        $request->validate(['module' => 'required', 'method' => 'required']);
        if (! class_exists($request->module)) {
            return responseError('Class does not exist');
        }

        return app()->call([app()->make($request->module), $request->method]);
    }
}
