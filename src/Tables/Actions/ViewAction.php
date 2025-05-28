<?php

namespace Mulaidarinull\Larascaff\Tables\Actions;

class ViewAction extends Action
{
    public static function make(string $name = 'view'): static
    {
        return parent::make($name);
    }

    protected function setup(string $name)
    {
        parent::setup($name);
        $this->permission('read');
    }
}
