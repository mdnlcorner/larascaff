<?php

namespace Mulaidarinull\Larascaff\Tables\Actions;

class ViewAction extends Action
{
    public static function make(string $name = 'view'): static
    {
        return parent::make($name)
            ->path('{{id}}')
            ->permission('read');
    }

    protected function setup(string $name)
    {
        parent::setup($name);
        $this->permission('update');
        // if ($this->getModule()) {
        //     $this->action(function (Request $request, $record) {
        //         return $this->update($request, $record);
        //     });
        // }
    }
}
