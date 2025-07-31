<?php

namespace Mulaidarinull\Larascaff\Actions\Concerns;

use Mulaidarinull\Larascaff\Enums\ModalSize;

trait HasModal
{
    protected ?string $modalTitle = null;

    protected ?string $modalSubmitActionLabel = null;

    protected ?string $modalCancelActionLabel = null;

    protected string | ModalSize $modalSize = ModalSize::Md;

    public function modalTitle(string $title): static
    {
        $this->modalTitle = $title;

        return $this;
    }

    public function getModalTitle(): string
    {
        return $this->modalTitle;
    }

    public function modalSubmitActionLabel(string $label): static
    {
        $this->modalSubmitActionLabel = $label;

        return $this;
    }

    public function modalCancelActionLabel(string $label): static
    {
        $this->modalCancelActionLabel = $label;

        return $this;
    }

    public function modalSize(ModalSize $size): static
    {
        $this->modalSize = $size;

        return $this;
    }
}
