<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Mulaidarinull\Larascaff\Forms\Concerns\HasColumnSpan;
use Mulaidarinull\Larascaff\Forms\Concerns\HasField;
use Mulaidarinull\Larascaff\Forms\Concerns\HasModule;
use Mulaidarinull\Larascaff\Forms\Concerns\HasRelationship;
use Mulaidarinull\Larascaff\Forms\Concerns\HasValidation;
use Mulaidarinull\Larascaff\Forms\Contracts\HasField as ContractsHasField;

class Field implements ContractsHasField
{
    use HasColumnSpan;
    use HasField;
    use HasModule;
    use HasRelationship;
    use HasValidation;

    public static function make(string $name): static
    {
        $static = resolve(static::class);

        $static->name = $name;

        $static->label = ucwords(str_replace('_', ' ', $name));

        return $static;
    }

    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
