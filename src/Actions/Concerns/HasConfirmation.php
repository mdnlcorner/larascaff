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

    public function requiresConfirmation(): static
    {
        $this->confirmation();

        $this->modalTitle('Delete');

        $this->modalDescription('Are you sure you would like to do this?');

        $this->modalSubmitActionLabel('Confirm');

        $this->modalCancelActionLabel('Cancel');

        return $this;
    }
}
