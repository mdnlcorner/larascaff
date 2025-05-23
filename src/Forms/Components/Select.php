<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Mulaidarinull\Larascaff\Forms\Concerns\HasRelationship;

class Select extends Field
{
    use HasRelationship;

    protected array $options = [];

    protected bool $multiple = false;

    protected bool $searchable = false;

    protected ?int $limit = null;

    protected string | Model | null $serverSide = null;

    protected ?string $dependValue = null;

    protected ?string $dependColumn = null;

    protected ?string $depend = null;

    protected ?string $data = null;

    protected ?Model $model = null;

    protected ?string $columnLabel = null;

    protected ?string $columnValue = null;

    protected \Closure | string | null $modifyQuery = null;

    protected ?string $relationship = null;

    public function relationship(?string $name, string $label = 'name'): static
    {
        $this->relationship = $name;
        $this->searchable = true;
        $this->columnLabel($label);

        if (is_null($name)) {
            $this->relationship = $this->name;
        }

        return $this;
    }

    protected function setRelationshipValue(): void
    {
        if ($this->relationship) {
            if (! $this->columnValue) {
                $this->columnValue('id');
            }
            if (str_contains($this->relationship, '.')) {
                $relationships = explode('.', $this->relationship);
                $value = getRecord();
                foreach ($relationships as $item) {
                    $value = $value?->{$item};
                    $model = ($model ?? getRecord())->{$item}()->getRelated();
                }
                $this->value = $value?->id;
            } else {
                $model = getRecord()->{$this->relationship}()->getRelated();
                if (
                    getRecord()->{$this->relationship}() instanceof \Illuminate\Database\Eloquent\Relations\MorphToMany ||
                    getRecord()->{$this->relationship}() instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany
                ) {
                    $this->value = getRecord()->{$this->relationship}->pluck($this->columnValue)->implode(',');
                } elseif (getRecord()->{$this->relationship}() instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo) {
                    $this->value = getRecord($this->getName());
                }
            }
            $this->serverSide = $model;
        }
    }

    protected function setServerSideOptions(): void
    {
        if ($this->serverSide) {
            $options = $this->serverSide->when($this->modifyQuery, $this->modifyQuery)
                ->when($this->value, function ($query) {
                    if (getRecord()->{$this->relationship} instanceof Model) {
                        $query->where($this->columnValue, '!=', getRecord()->{$this->relationship}->{$this->columnValue});
                    } else {
                        $query->whereNotIn($this->columnValue, getRecord()->{$this->relationship}->pluck($this->columnValue));
                    }
                })
                ->limit($this->limit ?? 20)
                ->get()
                ->map(function ($item) {
                    $res = [
                        'label' => $item->{$this->columnLabel},
                        'value' => $item->{$this->columnValue},
                    ];

                    return $res;
                });

            if ($this->value) {
                if (getRecord()->{$this->relationship} instanceof Model) {
                    $options->prepend([
                        'label' => getRecord()->{$this->relationship}->{$this->columnLabel},
                        'value' => getRecord()->{$this->relationship}->{$this->columnValue},
                        'selected' => 'true',
                    ]);
                } else {
                    foreach (getRecord()->{$this->relationship} as $item) {
                        $options->prepend([
                            'label' => $item->{$this->columnLabel},
                            'value' => $item->{$this->columnValue},
                            'selected' => 'true',
                        ]);
                    }
                }
            }

            $this->options = $options->toArray();
            $this->serverSide = get_class($this->serverSide);
        }

    }

    public function serverSideOptionsHandler(Request $request)
    {
        $this->columnValue = $request->get('columnValue') ?? 'id';
        $this->columnLabel = $request->get('columnLabel') ?? 'name';

        try {
            $data = $request->get('serverSide')::query()
                ->select($this->columnValue, $this->columnLabel)
                ->when($request->filled('modifyQuery'), function (Builder $query) use ($request) {
                    $moduleName = explode('@', $request->get('modifyQuery'));

                    try {
                        $module = $moduleName[0];
                        $name = $moduleName[1];
                        setRecord($module::getInstanceModel());
                        foreach ($module::formBuilder(new \Mulaidarinull\Larascaff\Forms\Components\Form)->getComponents() as $component) {
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
                ->when($request->filled('value') && ! $request->filled('search'), function (Builder $query) use ($request) {
                    // multipe select
                    if (str_contains($request->get('value'), ',')) {
                        $value = explode(',', $request->get('value'));

                        return $query->whereNotIn($this->columnValue, $value);
                    }
                    $query->where($this->columnValue, '!=', $request->get('value'));
                })
                ->when($request->filled('dependValue') && $request->filled('dependColumn'), function (Builder $query) use ($request) {
                    $query->where($request->get('dependColumn'), $request->get('dependValue'));
                })
                ->when($request->filled('search'), function ($query) use ($request) {
                    $query->where($this->columnLabel, 'like', "%{$request->get('search')}%");
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
                if (str_contains($request->get('value'), ',')) {
                    $value = explode(',', $request->get('value'));
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

    public function modifyQuery(\Closure $cb): static
    {
        $this->modifyQuery = $cb;

        return $this;
    }

    public function getModifyQuery(): \Closure | string | null
    {
        return $this->modifyQuery;
    }

    /**
     * limit of record shown, max 100
     */
    public function limit(int $limit): static
    {
        $this->limit = $limit;
        if ($limit > 100) {
            $this->limit = 100;
        }

        return $this;
    }

    public function dependValue(?string $dependValue): static
    {
        $this->dependValue = $dependValue;

        return $this;
    }

    public function columnLabel(?string $columnLabel = 'name'): static
    {
        $this->columnLabel = $columnLabel;

        return $this;
    }

    public function columnValue(?string $columnValue = 'id'): static
    {
        $this->columnValue = $columnValue;

        return $this;
    }

    public function depend(bool $depend = true): static
    {
        $this->depend = $depend;

        return $this;
    }

    public function dependColumn(string $dependColumn): static
    {
        $this->dependColumn = $dependColumn;

        return $this;
    }

    public function serverSide(string $model): static
    {
        $this->serverSide = $model;

        return $this;
    }

    public function options($options): static
    {
        $this->options = $options;

        return $this;
    }

    public function multiple(bool $multiple = true): static
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function searchable(bool $searchable = true): static
    {
        $this->searchable = $searchable;

        return $this;
    }

    public function view(): string
    {
        $this->setRelationshipValue();
        $this->setServerSideOptions();

        if ($this->modifyQuery) {
            if (method_exists($this, 'getModule')) {
                $this->modifyQuery = $this->getModule() . '@' . $this->name;
            }
        }

        return Blade::render(
            <<<'HTML'
            <x-larascaff::forms.select 
                :options="$options" 
                :value="$value" 
                :searchable="$searchable" 
                :name="$name" 
                :label="$label"
                :multiple="$multiple" 
                :placeholder="$placeholder"
                :serverSide="$serverSide"
                :dependColumn="$dependColumn"
                :dependValue="$dependValue"
                :depend="$depend"
                :modifyQuery="$modifyQuery"
                :columnLabel="$columnLabel"
                :columnValue="$columnValue"
                :columnSpan="$columnSpan"
                :limit="$limit"
            />
            HTML,
            [
                'name' => $this->name,
                'label' => $this->label,
                'options' => $this->options,
                'placeholder' => $this->placeholder,
                'multiple' => $this->multiple,
                'searchable' => $this->searchable,
                'serverSide' => $this->serverSide,
                'value' => $this->value,
                'dependColumn' => $this->dependColumn,
                'dependValue' => $this->dependValue,
                'depend' => $this->depend,
                'modifyQuery' => $this->modifyQuery,
                'columnLabel' => $this->columnLabel,
                'columnValue' => $this->columnValue,
                'columnSpan' => $this->columnSpan,
                'limit' => $this->limit,
            ]
        );
    }
}
