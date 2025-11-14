<?php

namespace Mulaidarinull\Larascaff\Actions\Concerns;

use Mulaidarinull\Larascaff\Forms\Components\Field;

trait HasValidation
{
    protected array $validations = [];

    protected bool $withValidations = true;

    public function withValidations(bool $status = true): static
    {
        $this->withValidations = $status;

        return $this;
    }

    protected function fillValidations(Field $field, ?string $relationship)
    {
        if ($relationship) {
            if (count($field->getValidations()) && ! $this->getModule()::getInstanceModel()->{$relationship}() instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
                $field->parentRelationship($relationship);
                $field->name($relationship.'.'.$field->getName());
                $this->set($field);
            }
        } else {
            $this->set($field);
        }
    }

    protected function set($field)
    {
        foreach ($field->getValidations()['validations'] ?? [] as $key => $validation) {
            $this->validations['validations'][$field->getName()][] = $validation;
        }
        foreach ($field->getValidations()['messages'] ?? [] as $key => $validation) {
            $this->validations['messages'][$field->getName().'.'.$key] = $validation;
        }
    }
}
