<?php

namespace Mulaidarinull\Larascaff\Tables\Actions;

use Mulaidarinull\Larascaff\Forms\Components\Form;
use Mulaidarinull\Larascaff\Info\Components\Info;

class ViewAction extends Action
{
    public static function make(string $name = 'view'): static
    {
        return parent::make($name);
    }

    protected function setup(string $name)
    {
        $this->label = str($name)->headline()->value();
        $this->name = $name;
        $this->permission('read');
        if (request()->has(['_action_handler', '_action_name', '_action_type', '_id']) && request()->ajax()) {
            $this->module(request()->post('_action_handler'));
            $this->formData = request()->except([
                '_token',
                '_method',
                '_action_handler',
                '_action_name',
                '_action_type',
                '_id',
            ]);

            $this->form(function (Form $form, Info $info) {
                $infoList = $this->getModule()::infoList($info);
                if ($infoList->getComponents()?->count()) {
                    return $infoList;
                }

                return $this->getModule()::formBuilder($form);
            });
        }
    }
}
