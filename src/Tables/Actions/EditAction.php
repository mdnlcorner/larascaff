<?php

namespace Mulaidarinull\Larascaff\Tables\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class EditAction extends Action
{
    public static function make(string $name = 'edit'): static
    {
        $static = parent::make('edit');

        return $static;
    }

    protected function setup(string $name)
    {
        parent::setup($name);
        $this->permission('update');
        if ($this->getModule()) {
            $this->action(function (Request $request, $record) {
                return $this->update($request, $record);
            });
        }
    }

    protected function update(Request $request, Model $record): \Illuminate\Http\JsonResponse
    {
        Gate::authorize($this->getPermission() . ' ' . $this->getModule()::getUrl());

        $this->inspectFormBuilder($this->getForm()->getComponents());

        dd($this->validations);
        $request->validate($this->validations['validations'] ?? [], $this->validations['messages'] ?? []);

        DB::beginTransaction();

        try {
            $this->callModifyFormData($this->modifyFormData);
            $record->fill($this->formData);

            $this->callHook($this->beforeSave);

            $this->oldModelValue = $record->replicate();

            $record->save();

            foreach ($this->getMedia() as $input) {
                $this->uploadMediaHandler(input: $input, model: $record);
            }

            foreach($this->getRelationship() as $input) {
                $this->relationshipHandler(input: $input, model: $record);
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
