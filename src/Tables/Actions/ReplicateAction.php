<?php

namespace Mulaidarinull\Larascaff\Tables\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Mulaidarinull\Larascaff\Forms\Components\Form;

class ReplicateAction extends Action
{
    public static function make(string $name = 'replicate'): static
    {
        return parent::make('replicate');
    }

    protected function setup(string $name)
    {
        parent::setup($name);
        $this->permission('replicate');
        $this->icon('tabler-copy');

        if ($this->getModule()) {
            $this->form(function(Form $form) {
                return $form->schema([]);
            });

            $this->action = function (Request $request, $record) {
                return $this->actionHandler($request, $record);
            };
        }
    }
}
