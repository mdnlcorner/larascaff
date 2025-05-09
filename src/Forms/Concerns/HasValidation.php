<?php

namespace Mulaidarinull\Larascaff\Forms\Concerns;

trait HasValidation
{
    protected array $validations = [];

    public function validations(array $validations, array $messages = [])
    {
        foreach ($validations as $validation) {
            $this->validations['validations'][$this->name][] = $validation;
        }
        foreach ($messages as $key => $message) {
            $this->validations['messages'][$this->name . '.' . $key] = $message;
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
}
