<?php

namespace Mulaidarinull\Larascaff;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OptionsSelectServerSide
{
    protected Request $request;

    protected string $columnValue;

    protected string $columnLabel;

    public function __invoke(Request $request)
    {
        $this->request = $request;
        $this->columnValue = $request->get('columnValue') ?? 'id';
        $this->columnLabel = $request->get('columnLabel') ?? 'name';

        try {
            $data = $request->get('serverSide')::query()
                ->select($this->columnValue, $this->columnLabel)
                ->when($request->filled('modifyQuery'), function (Builder $query) {
                    $moduleName = explode('@', $this->request->get('modifyQuery'));
                    try {
                        $module = $moduleName[0];
                        $name = $moduleName[1];
                        setRecord($module::getInstanceModel());
                        foreach ($module::formBuilder(new \Mulaidarinull\Larascaff\Components\Forms\Form)->getComponents() as $component) {
                            if (method_exists($component, 'getComponents')) {
                                foreach ($component->getComponents() as $childComp) {
                                    if ($childComp->getName() == $name) {
                                        $childComp->getModifyQuery()($query);
                                    }
                                }
                            } elseif ($component->getName() == $name) {
                                $component->getModifyQuery()($query);
                            }
                        }
                    } catch (\Throwable $th) {
                        throw new \Exception($th->getMessage());
                    }
                })
                ->when($request->filled('value') && ! $request->filled('search'), function (Builder $query) {
                    // multipe select
                    if (str_contains($this->request->get('value'), ',')) {
                        $value = explode(',', $this->request->get('value'));

                        return $query->whereNotIn($this->columnValue, $value);
                    }
                    $query->where($this->columnValue, '!=', $this->request->get('value'));
                })
                ->when($request->filled('dependValue') && $request->filled('dependColumn'), function (Builder $query) {
                    $query->where($this->request->get('dependColumn'), $this->request->get('dependValue'));
                })
                ->when($request->filled('search'), function ($query) {
                    $query->where($this->columnLabel, 'like', "%{$this->request->get('search')}%");
                })
                ->take($request->limit ? ($request->limit > 100 ? 100 : $request->limit) : 20)
                ->get()
                ->map(function ($item) {
                    $res = [
                        'label' => $item->{$this->columnLabel},
                        'value' => $item->{$this->columnValue},
                    ];

                    return $res;
                });

            if ($request->filled('value') && ! $request->filled('search')) {
                // multiple select
                if (str_contains($this->request->get('value'), ',')) {
                    $value = explode(',', $this->request->get('value'));
                    $getData = $request->get('serverSide')::query()->whereIn($this->columnValue, $value)->get();
                    foreach ($getData as $item) {
                        $data->prepend([
                            'label' => $item->{$this->columnLabel},
                            'value' => $item->{$this->columnValue},
                            'selected' => 'true',
                        ]);
                    }
                } else {
                    $getData = $request->get('serverSide')::query()->where($this->columnValue, $request->get('value'))->first();
                    if ($getData) {
                        $data->prepend([
                            'label' => $getData->{$this->columnLabel},
                            'value' => $getData->{$this->columnValue},
                            'selected' => 'true',
                        ]);
                    }
                }
            }

            return response()->json($data);
        } catch (\Throwable $th) {
            return responseError($th);
        }
    }
}
