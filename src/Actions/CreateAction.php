<?php

namespace Mulaidarinull\Larascaff\Actions;

use Mulaidarinull\Larascaff\Enums\ColorVariant;

class CreateAction extends Action
{
    public static function make(string $name = 'create'): static
    {
        return parent::make($name)
            ->permission('create')
            ->icon('tabler-plus')
            ->path('/create')
            ->color(ColorVariant::Primary)
            ->blank(false);
    }
}
