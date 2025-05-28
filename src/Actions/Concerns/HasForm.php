<?php

namespace Mulaidarinull\Larascaff\Actions\Concerns;

use Closure;
use Illuminate\Support\Collection;

trait HasForm
{
    protected array $formData = [];

    protected Closure | bool | null $form = null;

    protected bool $hasForm = true;

    protected ?Closure $modifyFormData = null;

    public function modifyFormData(callable $callback): static
    {
        $this->modifyFormData = $callback;

        return $this;
    }

    protected function callModifyFormData($callback)
    {
        if ($callback) {
            $data = $this->resolveClosureParams($callback, $this->getOptions()[$this->getName()]);
            if (! $data) {
                throw new \Exception('Closure in modifyFormData must return array $data');
            }
            $this->formData = $data;
        }
    }

    public function form(Closure | bool | null $form = null): static
    {
        $this->form = $form;
        $this->hasForm = true;
        if (is_bool($form)) {
            $this->hasForm = $form;
        }

        return $this;
    }

    public function getForm()
    {
        return app()->call($this->form);
    }

    public function getFormData(): array
    {
        return $this->formData;
    }

    protected function inspectFormBuilder(Collection $fields, mixed $relationship = null)
    {
        foreach ($fields as $field) {
            if (method_exists($field, 'getValidations')) {
                $this->fillValidations($field, $relationship);
            }

            if (method_exists($field, 'numberFormat')) {
                if ($field->getNumberFormat()) {
                    if ($relationship) {
                        $explode = explode('.', $field->getName());
                        if (isset($this->formData[$relationship][$explode[count($explode) - 1]])) {
                            $this->formData[$relationship][$explode[count($explode) - 1]] = removeNumberFormat($this->formData[$relationship][$explode[count($explode) - 1]]);
                        }
                    } else {
                        if (isset($this->formData[$field->getName()])) {
                            $this->formData[$field->getName()] = removeNumberFormat($this->formData[$field->getName()]);
                        }
                    }
                }
            }

            if (method_exists($field, 'getComponents')) {
                $relationship = $field->getRelationship();
                $this->inspectFormBuilder($field->getComponents(), $relationship);
            }

            $this->addRelationshipToBeHandled($field);

            $this->addMediaToBeHandled($field);
        }
    }
}
