<?php

namespace Mulaidarinull\Larascaff\Tables\Actions;

use Illuminate\Http\Request;
use Mulaidarinull\Larascaff\Enums\ColorVariant;

class EditAction extends Action
{
    public static function make(?string $name = 'edit'): static
    {
        return parent::make('edit');
    }

    protected function setup(string $name)
    {
        parent::setup($name);

        $this->permission('update');

        $this->color(ColorVariant::Warning);

        $this->icon('tabler-edit');

        $this->notificationTitle('Updated Successfully');

        $this->modalSubmitActionLabel('Save Changes');

        if ($this->getModule()) {
            $this->action = function (Request $request, $record) {
                return $this->actionHandler($request, $record);
            };
        }
    }
}
