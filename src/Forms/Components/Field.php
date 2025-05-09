<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Mulaidarinull\Larascaff\Forms\Concerns\HasColumnSpan;
use Mulaidarinull\Larascaff\Forms\Concerns\HasField;
use Mulaidarinull\Larascaff\Forms\Concerns\HasModule;
use Mulaidarinull\Larascaff\Forms\Concerns\HasValidation;

class Field
{
    use HasColumnSpan;
    use HasField;
    use HasModule;
    use HasValidation;

    protected string | array $name = '';

    public static function make($name): static
    {
        $static = app(static::class);
        $static->name($name);
        $static->label = ucwords(str_replace('_', ' ', $name));

        return $static;
    }

    public function name(string | array $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string | array
    {
        return $this->name;
    }
}
