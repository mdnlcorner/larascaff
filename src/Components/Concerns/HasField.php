<?php

namespace Mulaidarinull\Larascaff\Components\Concerns;

trait HasField
{
    protected ?string $type = null;

    protected ?string $label = null;

    protected ?string $placeholder = null;

    protected string|int|array|null $value = null;

    protected bool $disabled = false;

    protected bool $readonly = false;

    public function disabled(bool $disabled = true)
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function readonly(bool $readonly = true)
    {
        $this->readonly = $readonly;

        return $this;
    }

    public function value($value)
    {
        $this->value = $value;

        return $this;
    }

    public function type(string $type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getRelationship()
    {
        return $this->relationship ?? null;
    }

    public function label(string $name)
    {
        $this->label = $name;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function placeholder(?string $name)
    {
        $this->placeholder = $name;

        return $this;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    public function getValue(): string|int|array|null
    {
        return $this->value;
    }
}
