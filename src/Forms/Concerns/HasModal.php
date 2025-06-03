<?php

namespace Mulaidarinull\Larascaff\Forms\Concerns;

use Mulaidarinull\Larascaff\Enums\ModalSize;

trait HasModal
{
    protected ?string $title = null;

    protected ModalSize $modalSize = ModalSize::Md;

    protected string $actionLabel = 'Save';

    public function actionLabel(string $actionLabel): static
    {
        $this->actionLabel = $actionLabel;

        return $this;
    }

    public function getActionLabel(): string
    {
        return $this->actionLabel;
    }

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        if (! $this->title) {
            if ($this->getModule()::getInstanceModel()) {
                $this->title = 'Form ' . str(ucwords(str_replace('_', ' ', $this->getModule()::getInstanceModel()->getTable())))->singular();
            }
        }

        return $this->title;
    }

    public function modalSize(ModalSize $modalSize): static
    {
        $this->modalSize = $modalSize;

        return $this;
    }

    public function getModalSize(): string
    {
        return $this->modalSize->value;
    }
}
