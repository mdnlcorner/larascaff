<?php

namespace Mulaidarinull\Larascaff\Components\Forms;

use Mulaidarinull\Larascaff\Components\Concerns\HasColumnSpan;
use Mulaidarinull\Larascaff\Components\Concerns\HasField;
use Mulaidarinull\Larascaff\Components\Concerns\HasModule;

class Field
{
    use HasColumnSpan, HasModule, HasField;

    protected string|array $name = '';

    public static function make($name): static
    {
        $static = app(static::class);
        $static->name($name);
        $static->label = ucwords(str_replace('_', ' ', $name));

        return $static;
    }

    public function name(string|array $name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
}