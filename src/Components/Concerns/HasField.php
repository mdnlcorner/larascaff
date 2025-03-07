<?php

namespace Mulaidarinull\Larascaff\Components\Concerns;

trait HasField
{
    protected string $type = '';

    protected ?string $label = '';

    protected string|array $placeholder = '';

    protected string|array|null $value = null;

    protected bool $disabled = false;

    protected bool $readonly = false;

    protected array $validations = [];

    public function disabled(bool $disabled = true)
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function validations(array $validations, array $messages = [])
    {
        foreach ($validations as $validation) {
            $this->validations['validations'][$this->name][] = $validation;
        }
        foreach ($messages as $key => $message) {
            $this->validations['messages'][$this->name.'.'.$key] = $message;
        }

        return $this;
    }

    public function required(bool $required = true)
    {
        $this->validations(['required']);

        return $this;
    }

    public function getValidations(): array
    {
        return $this->validations;
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

    public function placeholder(string|array $name)
    {
        $this->placeholder = $name;

        return $this;
    }
}
