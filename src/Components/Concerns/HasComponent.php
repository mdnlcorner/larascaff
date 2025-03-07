<?php

namespace Mulaidarinull\Larascaff\Components\Concerns;

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

    public function getName()
    {
        return $this->name;
    }

    public function schema(array $components)
    {
        $this->components = collect($components);
        $e = new \Exception;
        $this->module = $e->getTrace()[1]['class'] ?? null;

        return $this;
    }
}
