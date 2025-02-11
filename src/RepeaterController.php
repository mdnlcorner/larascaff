<?php

namespace Mulaidarinull\Larascaff;

use Illuminate\Http\Request;
use Mulaidarinull\Larascaff\Components\Forms\Form;

class RepeaterController
{
    public function __invoke(Request $request)
    {
        $class = (new ('\\'.$request->post('module')));
        setRecord($class->getModel());
        foreach($class->formBuilder(new Form)->getComponents() as $repeater) {
            if ($repeater instanceof \Mulaidarinull\Larascaff\Components\Layouts\Repeater) {
                $validations = [];
                $validationMessages = [];
                foreach($repeater->getComponents() as $component) {
                    foreach($component->getValidations()['validations'] ?? [] as $key => $validation) {
                        $validations[$key] = $validation;
                    }
                    foreach($component->getValidations()['messages'] ?? [] as $key => $validation) {
                        $validationMessages[$key] = $validation;
                    }
                }
                $validated = $request->validate($validations, $validationMessages);
                return $repeater->getHandleAddRows()($validated);
            }
        }
    }
}