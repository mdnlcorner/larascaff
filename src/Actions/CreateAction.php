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

        $this->label(__('larascaff::action.label.create'));

        $this->notificationTitle(__('larascaff::action.notification.create.title'));

        $this->modalSubmitActionLabel(__('larascaff::action.modal.create.title'));

        if ($this->getModule()) {
            $this->action = function (Request $request, $record) {
                return $this->actionHandler($request, $record);
            };
        }
    }
}
