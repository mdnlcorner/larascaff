<?php

namespace Mulaidarinull\Larascaff\Tables\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Mulaidarinull\Larascaff\Forms\Components\Form;

class DeleteAction extends Action
{
    protected bool $confirmation = true;

    public static function make(?string $name = 'delete'): static
    {
        return parent::make('delete');
    }

    protected function setup(string $name)
    {
        parent::setup($name);

        $this->permission($name);

        $this->form(false);

        if ($this->getModule()) {
            $this->action = function ($record) {
                return $this->handle($record);
            };
        }
    }

    protected function handle(Model $record)
    {
        Gate::authorize($this->getPermission() . ' ' . $this->getModule()::getUrl());

        if ($form = Arr::get($this->getModule()::getActions(), 'create.form')) {
            $this->form($form);
        } else {
            $this->form(fn (Form $form) => $this->getModule()::formBuilder($form));
        }

        $this->inspectFormBuilder($this->getForm()->getComponents());

        DB::beginTransaction();

        try {
            $this->callHook($this->beforeSave);

            foreach ($this->getRelationship() as $input) {
                $this->relationshipHandler(input: $input, model: $record);
            }

            foreach ($this->getMedia() as $input) {
                $this->deleteMediaHandler(input: $input, model: $record);
            }

            $record->delete();

            $this->callHook($this->afterSave);

            DB::commit();

            return responseSuccess();
        } catch (\Throwable $th) {
            DB::rollBack();

            return responseError($th);
        }
    }
}
