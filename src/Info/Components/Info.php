<?php

namespace Mulaidarinull\Larascaff\Info\Components;

use Closure;
use Mulaidarinull\Larascaff\Enums\ModalSize;
use Mulaidarinull\Larascaff\Forms\Concerns\HasColumnSpan;
use Mulaidarinull\Larascaff\Forms\Concerns\HasComponent;
use Mulaidarinull\Larascaff\Forms\Concerns\HasModule;

class Info
{
    use HasColumnSpan;
    use HasComponent;
    use HasModule;

    protected ?string $label = '';

    protected string | array $placeholder = '';

    protected bool $show = true;

    protected $value = null;

    protected ?string $title = null;

    protected ?ModalSize $modalSize = ModalSize::Md;

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

    public function value($value)
    {
        $this->value = $value;

        return $this;
    }

    public function show(bool | Closure $show = true)
    {
        if (is_callable($show)) {
            $this->show = $show(getRecord());
        } else {
            $this->show = $show;
        }

        return $this;
    }

    public function getShow()
    {
        return $this->show;
    }

    public static function make(string $name): static
    {
        $static = app(static::class);
        $static->name = $name;
        $static->label = $name;

        return $static;
    }

    public function name(string | array $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function label(?string $name = null)
    {
        $this->label = $name;

        return $this;
    }

    public function placeholder(string | array $name)
    {
        $this->placeholder = $name;

        return $this;
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
            $view .= $component->view();
        }

        return $view;
    }
}
