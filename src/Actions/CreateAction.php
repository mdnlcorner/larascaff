<?php

namespace Mulaidarinull\Larascaff\Actions;

use Illuminate\Http\Request;

class CreateAction extends Action
{
    public static function make(?string $name = 'create'): static
    {
        $static = parent::make('create');

        return $static;
    }

    protected function setup(string $name)
    {
        parent::setup($name);

        $this->permission($name);

        if ($this->getModule()) {
            $this->action = function (Request $request, $record) {
                return $this->actionHandler($request, $record);
            };
        }
    }
}
