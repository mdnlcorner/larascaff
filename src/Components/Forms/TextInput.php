<?php

namespace Mulaidarinull\Larascaff\Components\Forms;

use Illuminate\Support\Facades\Blade;

class TextInput extends Field
{
    protected string $type = 'input';
    protected string $mask = '';
    protected array $removeMask = [];
    protected bool $revealable = false;
    protected string|null $appendIcon = null;
    protected string|null $appendIconBtn = null;
    protected string|null $prependIcon = null;
    protected string|null $prependIconBtn = null;
    protected array|null $numberFormat = null;

    public function mask(string $mask)
    {
        $this->mask = $mask;
        return $this;
    }

    public function password(bool $password = true)
    {
        $this->type = $password ? 'password' : 'text';
        return $this;
    }

    public function revealable(bool $revealable = true)
    {
        $this->revealable = $revealable;
        return $this;
    }

    public function appendIcon(string $appendIcon)
    {
        $this->appendIcon = $appendIcon;
        return $this;
    }

    public function appendIconBtn(string $appendIconBtn)
    {
        $this->appendIconBtn = $appendIconBtn;
        return $this;
    }

    public function prependIcon(string $prependIcon)
    {
        $this->prependIcon = $prependIcon;
        return $this;
    }

    public function prependIconBtn(string $prependIconBtn)
    {
        $this->prependIconBtn = $prependIconBtn;
        return $this;
    }

    public function numberFormat(?string $thousandSeparator = '.', ?string $decimalSeparator = ',')
    {
        $this->numberFormat = [$thousandSeparator, $decimalSeparator];
        return $this;
    }

    public function getNumberFormat()
    {
        return $this->numberFormat;
    }

    public function view()
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
                'name' => $this->name,
                'label' => $this->label,
                'placeholder' => $this->placeholder,
                'type' => $this->type,
                'mask' => $this->mask,
                'value' => $this->value,
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
