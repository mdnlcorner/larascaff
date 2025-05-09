<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Illuminate\Support\Facades\Blade;

class TextInput extends Field
{
    protected ?string $type = 'input';

    protected ?string $mask = null;

    protected array $removeMask = [];

    protected bool $revealable = false;

    protected ?string $appendIcon = null;

    protected ?string $appendIconBtn = null;

    protected ?string $prependIcon = null;

    protected ?string $prependIconBtn = null;

    protected ?array $numberFormat = null;

    public function mask(string $mask): static
    {
        $this->mask = $mask;

        return $this;
    }

    public function password(bool $password = true): static
    {
        $this->type = $password ? 'password' : 'text';

        return $this;
    }

    public function revealable(bool $revealable = true): static
    {
        $this->revealable = $revealable;

        return $this;
    }

    public function appendIcon(string $appendIcon): static
    {
        $this->appendIcon = $appendIcon;

        return $this;
    }

    public function appendIconBtn(string $appendIconBtn): static
    {
        $this->appendIconBtn = $appendIconBtn;

        return $this;
    }

    public function prependIcon(string $prependIcon): static
    {
        $this->prependIcon = $prependIcon;

        return $this;
    }

    public function prependIconBtn(string $prependIconBtn): static
    {
        $this->prependIconBtn = $prependIconBtn;

        return $this;
    }

    public function numberFormat(?string $thousandSeparator = '.', ?string $decimalSeparator = ',')
    {
        $this->numberFormat = [$thousandSeparator, $decimalSeparator];

        return $this;
    }

    public function getNumberFormat(): ?array
    {
        return $this->numberFormat;
    }

    public function view(): string
    {
        return Blade::render(
            <<<'HTML'
            <x-larascaff::forms.input 
                :appendIconBtn="$appendIconBtn" 
                :appendIcon="$appendIcon" 
                :prependIconBtn="$prependIconBtn" 
                :prependIcon="$prependIcon" 
                :columnSpan="$columnSpan" 
                :revealable="$revealable" 
                :name="$name" 
                :disabled="$disabled" 
                :readonly="$readonly" 
                :type="$type" 
                :label="$label" 
                :value="$type == 'password' ? '' : $value" 
                :placeholder="$placeholder" 
                :mask="$mask"
                :numberFormat="$numberFormat"
            />
            HTML,
            [
                'name' => $this->getName(),
                'label' => $this->getLabel(),
                'placeholder' => $this->getPlaceholder(),
                'type' => $this->getType(),
                'mask' => $this->mask,
                'value' => $this->getValue(),
                'disabled' => $this->disabled,
                'readonly' => $this->readonly,
                'columnSpan' => $this->columnSpan,
                'revealable' => $this->revealable,
                'appendIconBtn' => $this->appendIconBtn,
                'appendIcon' => $this->appendIcon,
                'prependIconBtn' => $this->prependIconBtn,
                'prependIcon' => $this->prependIcon,
                'numberFormat' => $this->numberFormat,
            ]
        );
    }
}
