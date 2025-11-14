<?php

namespace Mulaidarinull\Larascaff\Actions\Concerns;

use Mulaidarinull\Larascaff\Enums\ModalSize;

trait HasModal
{
    protected ?string $modalTitle = null;

    protected ?string $modalSubmitActionLabel = null;

    protected ?string $modalCancelActionLabel = null;

    protected string|ModalSize $modalSize = ModalSize::Md;

    protected ?string $modalIcon = null;

    protected ?string $modalDescription = null;

    public function modalIcon(string $modalIcon): static
    {
        $this->modalIcon = $modalIcon;

        return $this;
    }

    public function getModalIcon(): ?string
    {
        return $this->modalIcon;
    }

    public function modalDescription(string $modalDescription): static
    {
        $this->modalDescription = $modalDescription;

        return $this;
    }

    public function getModalDescription(): ?string
    {
        return $this->modalDescription;
    }

    public function modalTitle(string $title): static
    {
        $this->modalTitle = $title;

        return $this;
    }

    public function getModalTitle(): ?string
    {
        return $this->modalTitle;
    }

    public function modalSubmitActionLabel(string $label): static
    {
        $this->modalSubmitActionLabel = $label;

        return $this;
    }

    public function getModalSubmitActionLabel(): ?string
    {
        return $this->modalSubmitActionLabel;
    }

    public function modalCancelActionLabel(string $label): static
    {
        $this->modalCancelActionLabel = $label;

        return $this;
    }

    public function getModalCancelActionLabel(): ?string
    {
        return $this->modalCancelActionLabel;
    }

    public function modalSize(ModalSize $size): static
    {
        $this->modalSize = $size;

        return $this;
    }

    public function getModalSize(): string|ModalSize
    {
        return $this->modalSize;
    }
}
