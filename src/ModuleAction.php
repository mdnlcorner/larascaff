<?php

namespace Mulaidarinull\Larascaff;

use Illuminate\Http\Request;

class ModuleAction
{
    public function __invoke(Request $request)
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
