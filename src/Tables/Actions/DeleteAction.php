<?php

namespace Mulaidarinull\Larascaff\Tables\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Mulaidarinull\Larascaff\Forms\Components\Form;

class DeleteAction extends Action
{
    protected bool $confirmation = true;

    public static function make(string $name = 'delete'): static
    {
        return parent::make($name);
    }

    protected function setup(string $name)
    {
        parent::setup($name);
        $this->permission('delete');
        $this->form(false);

        if ($this->getModule()) {
            $this->action(function (Request $request, $record) {
                return $this->delete($request, $record);
            });
        }
    }

    protected function delete(Request $request, Model $record)
    {
        Gate::authorize($this->getPermission() . ' ' . $this->getModule()::getUrl());

        $this->form(fn (Form $form) => $this->getModule()::formBuilder($form));

        $this->inspectFormBuilder($this->getForm()->getComponents());

        DB::beginTransaction();

        try {
            $this->callHook($this->beforeSave);

            $record->delete();

            foreach ($this->getMedia() as $input) {
                $this->deleteMediaHandler(input: $input, model: $record);
            }

            $this->callHook($this->afterSave);

            DB::commit();

            return responseSuccess();
        } catch (\Throwable $th) {
            DB::rollBack();

            return responseError($th);
        }
    }
}
