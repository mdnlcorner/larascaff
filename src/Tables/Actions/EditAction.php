<?php

namespace Mulaidarinull\Larascaff\Tables\Actions;

use Illuminate\Http\Request;

class EditAction extends Action
{
    public static function make(string $name = 'edit'): static
    {
        return parent::make($name);
    }

    protected function setup(string $name)
    {
        parent::setup($name);
        $this->permission('update');

        if ($this->getModule()) {
            $this->action = function (Request $request, $record) {
                return $this->actionHandler($request, $record);
            };
        }
    }
}
