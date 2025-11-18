<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class Select extends Field
{
    protected array | Closure $options = [];

    protected bool $multiple = false;

    protected bool $searchable = false;

    protected int $limit = 20;

    protected string | Model | null $model = null;

    protected ?string $dependValue = null;

    protected ?string $dependColumn = null;

    protected array $dependTo = [];

    protected ?string $depend = null;

    protected ?string $data = null;

    protected ?string $columnLabel = null;

    protected ?string $columnValue = null;

    protected ?\Closure $query = null;

    protected ?string $relationship = null;

    public function dependTo(array $depends): static
    {
        $this->dependTo = $depends;

        return $this;
    }

    public function getDependTo(): array
    {
        return $this->dependTo;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function relationship(?string $name = null, ?string $label = 'name'): static
    {
        $this->relationship = $name;

        $this->searchable = true;

        $this->columnLabel($label);

        if (is_null($name)) {
            $this->relationship = $this->name;
        }

        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limit = $limit;
        if ($limit > 100) {
            $this->limit = 100;
        }

        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
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

    public function getColumnLabel(): string
    {
        return $this->columnLabel;
    }

    public function columnValue(?string $columnValue = 'id'): static
    {
        $this->columnValue = $columnValue;

        return $this;
    }

    public function getColumnValue(): string
    {
        return $this->columnValue;
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

    public function model(string $model, ?\Closure $query = null): static
    {
        $this->model = $model;

        $this->query = $query;

        return $this;
    }

    public function query(Closure $query): static
    {
        $this->query = $query;

        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function options(array | Closure $options): static
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

    protected function setRelationshipValue($component): void
    {
        if ($component->relationship) {
            if (!$component->columnValue) {
                $component->columnValue('id');
            }
            if (str_contains($component->relationship, '.')) {
                $relationships = explode('.', $component->relationship);
                $value = getRecord();
                foreach ($relationships as $item) {
                    $value = $value?->{$item};
                    $model = ($model ?? getRecord())->{$item}()->getRelated();
                }
                $component->value = $value?->id;
            } else {
                $model = getRecord()->{$component->relationship}()->getRelated();
                if (
                    getRecord()->{$component->relationship}() instanceof \Illuminate\Database\Eloquent\Relations\MorphToMany ||
                    getRecord()->{$component->relationship}() instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany
                ) {
                    $component->value = getRecord()->{$component->relationship}->pluck($component->columnValue)->implode(',');
                } elseif (getRecord()->{$component->relationship}() instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo) {
                    $component->value = getRecord($component->getName());
                }
            }
            if (is_string($model)) {
                $component->model = $model;
            } else {
                $component->model = get_class($model);
            }
        }
    }

    protected function addSelectedOptionIfNotExist($options, $value)
    {
        if (!$options->firstWhere('value', $value)) {
            $options->prepend([
                'label' => getRecord()->{$this->relationship}->{$this->columnLabel},
                'value' => $value,
                'selected' => 'true',
            ]);
        } else {
            $options = $options->map(function ($item) use ($value) {
                if ($item['value'] == $value) {
                    $item['selected'] = true;
                }

                return $item;
            });
        }

        return $options;
    }

    protected function setServerSideOptions(): void
    {
        if ($this->model) {
            if (is_string($this->model) && class_exists($this->model)) {
                $model = $this->model::query();
            } else {
                $model = $this->model->query();
            }
            $model->limit($this->limit);

            if ($this->query) {
                app()->call($this->query, ['query' => $model, 'dependValue' => $this->dependValue]);
            }

            $options = $model->pluck($this->columnValue, $this->columnLabel);

            if (is_array($options)) {
                $options = collect($options)->map(function ($item, $key) {
                    return [
                        'label' => $key,
                        'value' => $item,
                    ];
                })->values();
            } else {
                $options = $options->map(function ($item, $key) {
                    return [
                        'label' => $key,
                        'value' => $item,
                    ];
                })->values();
            }

            if ($this->value) {
                if ($this->relationship) {
                    if (getRecord()->{$this->relationship} instanceof Model) {
                        $value = getRecord()->{$this->relationship}->{$this->columnValue};
                        $options = $this->addSelectedOptionIfNotExist($options, $value);
                    } else {
                        foreach (getRecord()->{$this->relationship} as $item) {
                            $options->prepend([
                                'label' => $item->{$this->columnLabel},
                                'value' => $item->{$this->columnValue},
                                'selected' => 'true',
                            ]);
                        }
                    }
                    $this->model = $this->model;
                } else {
                    $model = $this->model::query()->where($this->columnValue, $this->value)->first();
                    $options = $this->addSelectedOptionIfNotExist($options, $model->{$this->columnValue});
                }
            }

            $this->options = $options->toArray();
        } else {
            if ($this->searchable) {
                if (is_array($this->options)) {
                    $this->options = collect($this->options)->map(function ($item, $key) {
                        $options = [
                            'label' => $key,
                            'value' => $item,
                        ];
                        if ($item == getRecord($this->name)) {
                            $options['selected'] = true;
                        }

                        return $options;
                    })->values()->toArray();
                } elseif ($this->options instanceof \Closure) {
                    $options = app()->call($this->options);
                    if (is_array($options)) {
                        $this->options = collect($options)->map(function ($item, $key) {
                            return [
                                'label' => $key,
                                'value' => $item,
                            ];
                        })->values()->toArray();
                    } else {
                        $this->options = $options->map(function ($item, $key) {
                            return [
                                'label' => $key,
                                'value' => $item,
                            ];
                        })->values()->toArray();
                    }
                }
            }
        }
    }

    protected function generateOptions($component, string $name, Request $request)
    {
        if (method_exists($component, 'getComponents')) {
            foreach ($component->getComponents() as $childComp) {
                if ($childComp->getName() == $name) {
                    $this->setRelationshipValue($childComp);

                    $model = (is_string($childComp->getModel()) ? new ($childComp->getModel()) : $childComp->getModel())::query();

                    if ($childComp->getQuery()) {
                        app()->call($childComp->getQuery(), ['query' => $model, 'dependValue' => $request->dependValue]);
                    }

                    $model->when($request->filled('search'), function ($query) use ($request, $childComp) {
                        $query->where($childComp->getColumnLabel(), 'like', "%{$request->get('search')}%");
                    });

                    return $model->select($childComp->getColumnLabel(), $childComp->getColumnValue())->get()
                        ->map(function ($item) use ($childComp) {
                            return [
                                'label' => $item->{$childComp->getColumnLabel()},
                                'value' => $item->{$childComp->getColumnValue()},
                            ];
                        })->toArray();
                }

                if ($options = $this->generateOptions($childComp, $name, $request, 1)) {
                    return $options;
                }
            }
        } elseif ($component->getName() == $name) {
            $this->setRelationshipValue($component);

            $model = (is_string($component->getModel()) ? new ($component->getModel()) : $component->getModel())::query();

            if ($component->getQuery()) {
                app()->call($component->getQuery(), ['query' => $model, 'dependValue' => $request->dependValue]);
            }

            $model->when($request->filled('search'), function ($query) use ($request, $component) {
                $query->where($component->getColumnLabel(), 'like', "%{$request->get('search')}%");
            });

            return $model->select($component->getColumnLabel(), $component->getColumnValue())->get()
                ->map(function ($item) use ($component) {
                    return [
                        'label' => $item->{$component->getColumnLabel()},
                        'value' => $item->{$component->getColumnValue()},
                    ];
                })->toArray();
        }
    }

    public function serverSideOptionsHandler(Request $request)
    {
        try {
            $moduleName = explode('@', $request->get('module'));

            $module = $moduleName[0];

            $componentName = $moduleName[1];

            setRecord($module::getInstanceModel());

            foreach ($module::formBuilder(new \Mulaidarinull\Larascaff\Forms\Components\Form)->getComponents() as $component) {
                $options = $this->generateOptions($component, $componentName, $request);

                if ($options) {
                    return response()->json($options);
                }
            }

            throw new \Exception('Not found');
        } catch (\Throwable $th) {
            return responseError($th);
        }
    }

    public function view(): string
    {
        $this->setRelationshipValue($this);

        $this->setServerSideOptions();

        if (method_exists($this, 'getModule')) {
            $this->module = $this->getModule() . '@' . $this->name;
        }

        return Blade::render(
            <<<'HTML'
            <x-larascaff::forms.select 
                :module="$module"
                :options="$options" 
                :value="$value" 
                :searchable="$searchable" 
                :name="$name" 
                :label="$label"
                :multiple="$multiple" 
                :placeholder="$placeholder"
                :model="$model"
                :dependColumn="$dependColumn"
                :dependValue="$dependValue"
                :depend="$depend"
                :columnLabel="$columnLabel"
                :columnValue="$columnValue"
                :columnSpan="$columnSpan"
                :limit="$limit"
                :attr="$attr"
                :dependTo="$dependTo"
            />
            HTML,
            [
                'module' => $this->module,
                'name' => $this->name,
                'label' => $this->label,
                'options' => is_array($this->options) ? $this->options : app()->call($this->options, []),
                'placeholder' => $this->placeholder,
                'multiple' => $this->multiple,
                'searchable' => $this->searchable,
                'model' => $this->model,
                'value' => $this->getValue(),
                'dependColumn' => $this->dependColumn,
                'dependValue' => $this->dependValue,
                'depend' => $this->depend,
                'columnLabel' => $this->columnLabel,
                'columnValue' => $this->columnValue,
                'columnSpan' => $this->columnSpan,
                'limit' => $this->limit,
                'attr' => $this->attr,
                'dependTo' => $this->dependTo,
            ]
        );
    }
}
