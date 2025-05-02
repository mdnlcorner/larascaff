<?php

namespace Mulaidarinull\Larascaff\Components\Forms;

use Mulaidarinull\Larascaff\Components\Concerns\HasColumnSpan;
use Mulaidarinull\Larascaff\Components\Concerns\HasField;
use Mulaidarinull\Larascaff\Components\Concerns\HasModule;
use Mulaidarinull\Larascaff\Components\Concerns\HasValidation;

class Field
{
    use HasColumnSpan;
    use HasField;
    use HasModule;
    use HasValidation;

    protected string|array $name = '';

    public static function make($name): static
    {
        $static = app(static::class);
        $static->name($name);
        $static->label = ucwords(str_replace('_', ' ', $name));

        return $static;
    }

    public function name(string|array $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string|array
    {
        return $this->name;
    }
}
