<?php

namespace Mulaidarinull\Larascaff\Tables\Actions;

use Mulaidarinull\Larascaff\Enums\ColorVariant;
use Mulaidarinull\Larascaff\Forms\Components\Form;
use Mulaidarinull\Larascaff\Info\Components\Info;

class ViewAction extends Action
{
    public static function make(?string $name = 'view'): static
    {
        return parent::make('view');
    }

    protected function setup(string $name)
    {
        parent::setup($name);

        $this->permission('read');

        $this->color(ColorVariant::Primary);

        $this->icon('tabler-eye');

        $this->handle();
    }

    protected function handle()
    {
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
