<?php

namespace Mulaidarinull\Larascaff\Tables\Actions;

class DeleteAction extends Action
{
    public static function make(string $name = 'delete'): static
    {
        return parent::make($name)
            ->path('{{id}}')
            ->method('delete')
            ->permission('delete');
    }
}
