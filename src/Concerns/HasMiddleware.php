<?php

namespace Mulaidarinull\Larascaff\Concerns;

trait HasMiddleware
{
    protected array $middleware = ['web'];

    protected array $authMiddleware = ['auth'];

    public function middleware(array $middleware): static
    {
        $this->middleware = $middleware;

        return $this;
    }

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function authMiddleware(array $authMiddleware): static
    {
        $this->authMiddleware = $authMiddleware;

        return $this;
    }

    public function getAuthMiddleware(): array
    {
        return $this->authMiddleware;
    }
}
