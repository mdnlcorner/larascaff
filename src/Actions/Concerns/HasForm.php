<?php

namespace Mulaidarinull\Larascaff\Actions\Concerns;

use Closure;
use Illuminate\Support\Collection;
use Mulaidarinull\Larascaff\Forms\Components\Form;

trait HasForm
{
    protected array $formData = [];

    protected ?Form $form = null;

    protected ?Closure $modifyFormData = null;

    public function modifyFormData(callable $callback): static
    {
        $this->modifyFormData = $callback;

        return $this;
    }

    protected function callModifyFormData($callback)
    {
        if ($callback) {
            $data = $this->resolveClosureParams($callback);
            if (! $data) {
                throw new \Exception('Closure in modifyFormData must return array $data');
            }
            $this->formData = $data;
        }
    }

    public function form(\Closure $form): static
    {
        $this->options['form'] = $form;

        return $this;
    }

    public function getForm()
    {
        return app()->call($this->options['form']);
    }

    public function getFormData(): array
    {
        return $this->formData;
    }

    protected function inspectFormBuilder(Collection $forms, mixed $relationship = null)
    {
        foreach ($forms as $form) {
            if (method_exists($form, 'getValidations')) {
                if ($relationship) {
                    if (count($form->getValidations()) && ! $this->getModule()::getInstanceModel()->{$relationship}() instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
                        $this->fillValidation($form);
                    }
                } else {
                    $this->fillValidation($form);
                }
            }

            if (method_exists($form, 'numberFormat')) {
                if ($form->getNumberFormat()) {
                    $this->formData[$form->getName()] = removeNumberFormat($this->formData[$form->getName()]);
                }
            }

            if (method_exists($form, 'getComponents')) {
                $relationship = $form->getRelationship();
                $this->inspectFormBuilder($form->getComponents(), $relationship);
            }

            $this->addRelationshipToBeHandled($form);

            $this->addMediaToBeHandled($form);
        }
    }
}
