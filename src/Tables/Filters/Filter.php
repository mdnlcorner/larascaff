<?php

namespace Mulaidarinull\Larascaff\Tables\Filters;

use Closure;
use Illuminate\Support\Facades\Blade;
use Mulaidarinull\Larascaff\Forms\Components\Field;

class Filter extends Field
{
    protected ?Closure $query = null;

    protected bool $toogle = false;

    protected string $variant = 'primary';

    public function __construct(protected string $name)
    {
        $this->name = $name;
    }

    public static function make(string $name): static
    {
        $static = app(static::class, ['name' => $name]);

        return $static;
    }

    public function query(Closure $cb): static
    {
        $this->query = $cb;
        
        return $this;
    }

    public function getQuery(): ?Closure
    {
        return $this->query;
    }

    public function toggle(bool $toggle): static
    {
        $this->toggle = $toggle;
        
        return $this;
    }

    public function variant(string $variant): static
    {
        $this->variant = $variant;

        return $this;
    }

    public function view()
    {
        return Blade::render(
            <<<'HTML'
            <x-larascaff::forms.checkbox 
                :columnSpan="$columnSpan" 
                :disabled="$disabled" 
                :readonly="$readonly" 
                :variant="$variant" 
                :value="$value" 
                :checked="$checked" 
                :name="$name" 
                :label="$label"
                :attr="$attr"
            />
            HTML,
            [
                'name' => $this->name,
                'label' => $this->label,
                'value' => $this->value,
                'variant' => $this->variant,
                'checked' => getRecord($this->name) ? true : false,
                'disabled' => $this->disabled,
                'readonly' => $this->readonly,
                'columnSpan' => $this->columnSpan,
                'attr' => $this->attr,
            ]
        );
    }
}