<?php

namespace Mulaidarinull\Larascaff\Actions\Concerns;

trait HasPermission
{
    protected string|bool $permission = false;

    public function permission(string|bool $permission): static
    {
        $this->permission = $permission;

        return $this;
    }

    public function getPermission(): string|bool
    {
        return $this->permission;
    }
}
