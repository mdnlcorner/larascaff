<?php

namespace Mulaidarinull\Larascaff\Forms\Concerns;

use Closure;

trait HasField
{
    protected Closure | string | null $name = null;

    protected Closure | string | null $type = null;

    protected Closure | string | null $label = null;

    protected Closure | string | null $placeholder = null;

    protected mixed $value = null;

    protected Closure | bool $disabled = false;

    protected Closure | bool $readonly = false;

    protected ?string $attr = '';

    protected \Closure | bool $show = true;

    public function disabled(Closure | bool $disabled = true)
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function getDisabled(): bool
    {
        return $this->resolveClosureParams($this->disabled);
    }

    public function attr(string $attr): static
    {
        $this->attr = $attr;

        return $this;
    }

    public function readonly(Closure | bool $readonly = true)
    {
        $this->readonly = $readonly;

        return $this;
    }

    public function getReadonly(): bool
    {
        return $this->resolveClosureParams($this->readonly);
    }

    public function value($value)
    {
        $this->value = $value;

        return $this;
    }

    public function type(Closure | string $type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->resolveClosureParams($this->type);
    }

    public function label(Closure | string $name)
    {
        $this->label = $name;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->resolveClosureParams($this->label);
    }

    public function placeholder(Closure | string $name)
    {
        $this->placeholder = $name;

        return $this;
    }

    public function getPlaceholder(): ?string
    {
        return $this->resolveClosureParams($this->placeholder);
    }

    public function show(\Closure | bool $status): static
    {
        $this->show = $status;

        return $this;
    }

    public function getShow(): bool
    {
        return $this->resolveClosureParams($this->show);
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
