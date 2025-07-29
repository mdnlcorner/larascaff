<?php

namespace Mulaidarinull\Larascaff\Tables\Actions;

use Illuminate\Http\Request;

class EditAction extends Action
{
    public static function make(?string $name = 'edit'): static
    {
        return parent::make('edit');
    }

    protected function setup(string $name)
    {
        parent::setup($name);

        $this->permission('update')
            ->notificationTitle('Updated Successfully');

        if ($this->getModule()) {
            $this->action = function (Request $request, $record) {
                return $this->actionHandler($request, $record);
            };
        }
    }
}
