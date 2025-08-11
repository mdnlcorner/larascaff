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

        $this->modalTitle(__('larascaff::action.modal.confirm.title', ['record' => '']));

        $this->modalDescription(__('larascaff::action.modal.confirm.description'));

        $this->modalSubmitActionLabel(__('larascaff::action.modal.confirm.action'));

        $this->modalCancelActionLabel(__('larascaff::action.modal.cancel.title'));

        return $this;
    }
}
