<?php

namespace Mulaidarinull\Larascaff\Actions\Concerns;

trait HasConfirmation
{
    protected bool $confirmation = false;

    public function confirmation(bool $confirmation = true): static
    {
        $this->confirmation = $confirmation;

        return $this;
    }

    public function hasConfirmation(): bool
    {
        return $this->confirmation;
    }
}
