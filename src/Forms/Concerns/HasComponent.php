<?php

namespace Mulaidarinull\Larascaff\Forms\Concerns;

use Closure;

trait HasComponent
{
    protected ?\Illuminate\Support\Collection $components = null;

    protected Closure | string | null $name = null;

    public function getComponents()
    {
        return $this->components;
    }

    public static function make(?string $name = null): static
    {
        $static = app(static::class);
        if ($name) {
            $static->name = $name;
        }

        return $static;
    }

    public function name(Closure | string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function schema(array $components)
    {
        $this->components = collect($components);

        return $this;
    }
}
