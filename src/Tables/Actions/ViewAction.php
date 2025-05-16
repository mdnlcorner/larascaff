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
}
