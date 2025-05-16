<?php

namespace Mulaidarinull\Larascaff\Tables\Actions;

class EditAction extends Action
{
    public static function make(string $name = 'edit'): static
    {
        return parent::make('edit')
            ->path('{{id}}/edit')
            ->permission('update');
    }
}
