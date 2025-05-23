<?php

namespace Mulaidarinull\Larascaff\Forms\Concerns;

trait HasComponent
{
    protected ?\Illuminate\Support\Collection $components = null;

    protected ?string $name = null;

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

    public function name(string $name): static
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
        $e = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        $this->module = $e[1]['class'] ?? null;

        return $this;
    }
}
