<?php

namespace Mulaidarinull\Larascaff\Info\Components;

use Closure;
use Mulaidarinull\Larascaff\Forms\Concerns;

class Info
{
    use Concerns\HasColumnSpan;
    use Concerns\HasComponent;
    use Concerns\HasModule;

    protected ?string $label = '';

    protected string|array $placeholder = '';

    protected bool $show = true;

    protected $value = null;

    public function value($value)
    {
        $this->value = $value;

        return $this;
    }

    public function show(bool|Closure $show = true)
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

    public function name(string|array $name)
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

    public function placeholder(string|array $name)
    {
        $this->placeholder = $name;

        return $this;
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
