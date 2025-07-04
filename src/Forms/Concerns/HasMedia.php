<?php

namespace Mulaidarinull\Larascaff\Forms\Concerns;

trait HasMedia
{
    protected string $disk = 'public';

    protected string $path = '';

    public function path(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function disk(string $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    public function getDisk(): string
    {
        return $this->disk;
    }
}
