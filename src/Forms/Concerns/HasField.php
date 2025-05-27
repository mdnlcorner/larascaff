<?php

namespace Mulaidarinull\Larascaff\Forms\Concerns;

trait HasField
{
    protected ?string $type = null;

    protected ?string $label = null;

    protected ?string $placeholder = null;

    protected mixed $value = null;

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

    public function getValue(): mixed
    {
        if ($this->getParentRelationship()) {
            preg_match_all('/\[(.*?)\]/', $this->getName(), $matches);

            if ($matches[1]) {
                if ($parentRelation = getRecord()->{$this->getParentRelationship()}) {
                    $this->value($parentRelation->{$matches[1][0]});
                    if (method_exists($this, 'numberFormat') && $this->numberFormat) {
                        $this->value(number_format($this->value, 0, $this->numberFormat[1], $this->numberFormat[0]));
                    }
                }
            }
        } else {
            if (method_exists($this, 'numberFormat')) {
                $this->value(is_null($this->value) ? ($this->numberFormat ? number_format(getRecord($this->name), 0, $this->numberFormat[1], $this->numberFormat[0]) : getRecord($this->name)) ?? '' : $this->value);
            } elseif (is_null($this->value)) {
                $this->value(getRecord($this->name));
            }
        }

        return $this->value;
    }
}
