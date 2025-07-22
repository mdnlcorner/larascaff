<?php

namespace Mulaidarinull\Larascaff\Assets;

abstract class Asset
{
    final public function __construct(protected string $id, protected ?string $path = null)
    {
    }

    public static function make(string $id, ?string $path = null): static
    {
        return app(static::class, ['id' => $id, 'path' => $path]);
    }

    public function getPath(): ?string
    {
        return $this->path;
    }
}