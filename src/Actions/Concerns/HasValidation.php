<?php

namespace Mulaidarinull\Larascaff\Actions\Concerns;

use Mulaidarinull\Larascaff\Forms\Components\Field;

trait HasValidation
{
    protected array $validations = [];

    protected function fillValidation(Field $field)
    {
        foreach ($field->getValidations()['validations'] ?? [] as $key => $validation) {
            $this->validations['validations'][$key] = $validation;
        }
        foreach ($field->getValidations()['messages'] ?? [] as $key => $validation) {
            $this->validations['messages'][$key] = $validation;
        }
    }
}
