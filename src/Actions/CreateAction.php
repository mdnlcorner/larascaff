<?php

namespace Mulaidarinull\Larascaff\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CreateAction extends Action
{
    public static function make(string $name = 'create'): static
    {
        $static = parent::make($name);

        return $static;
    }

    protected function setup(string $name)
    {
        parent::setup($name);
        $this->permission('create')
            ->method('POST');
        if ($this->getModule()) {
            $this->action(function (Request $request, $record) {
                return $this->store($request, $record);
            });
        }
    }

    protected function store(Request $request, Model $record): \Illuminate\Http\JsonResponse
    {
        Gate::authorize($this->getPermission() . ' ' . $this->getModule()::getUrl());

        $this->inspectFormBuilder($this->getForm()->getComponents());

        $request->validate($this->validations['validations'] ?? [], $this->validations['messages'] ?? []);

        DB::beginTransaction();

        try {
            $this->callModifyFormData($this->modifyFormData);
            $record->fill($this->formData);

            $this->callHook($this->beforeSave);

            $record->save();

            foreach ($this->getMedia() as $input) {
                $this->uploadMediaHandler(input: $input, model: $record);
            }

            foreach ($this->getRelationship() as $input) {
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
