<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Mulaidarinull\Larascaff\Forms\Concerns\HasColumnSpan;
use Mulaidarinull\Larascaff\Forms\Concerns\HasComponent;
use Mulaidarinull\Larascaff\Forms\Concerns\HasModule;
use Mulaidarinull\Larascaff\Enums\ModalSize;

class Form
{
    use HasColumnSpan;
    use HasComponent;
    use HasModule;

    protected ?string $title = null;

    protected ?ModalSize $modalSize = ModalSize::Md;

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
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

    public function render()
    {
        $view = '';
        foreach ($this->components as $component) {
            if (method_exists($component, 'module')) {
                $component->module($this->module);
            }
            $view .= $component->view();
        }

        return $view;
    }
}
