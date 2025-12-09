<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Mulaidarinull\Larascaff\Forms\Concerns;
use Mulaidarinull\Larascaff\Forms\Contracts\HasField as ContractsHasField;

class Field implements ContractsHasField
{
    use Concerns\HasColumnSpan;
    use Concerns\HasField;
    use Concerns\HasModule;
    use Concerns\HasRelationship;
    use Concerns\HasValidation;
    use Concerns\ResolveClosureParam;

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
        return $this->resolveClosureParams($this->name);
    }
}
