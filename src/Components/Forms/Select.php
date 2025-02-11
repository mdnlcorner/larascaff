<?php

namespace Mulaidarinull\Larascaff\Components\Forms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Mulaidarinull\Larascaff\Components\Concerns\HasRelationship;

class Select extends Field
{
    use HasRelationship;

    protected array $options = [];
    protected bool $multiple = false;
    protected bool $searchable = false;
    protected ?int $limit = null;
    protected string | null $serverSide = null;
    protected string | null $dependValue = null;
    protected string | null $dependColumn = null;
    protected string | null $depend = null;
    protected string | null $data = null;
    protected Model | null $model = null;
    protected string | null $columnLabel = null;
    protected string | null $columnValue = null;
    protected \Closure | string | null $modifyQuery = null;

    public function relationship(string|null $name = null, string $label)
    {
        $this->relationship = $name;
        $this->searchable = true;
        if (is_null($name)) {
            $this->relationship = $this->name;
        }
        if (str_contains($this->relationship, '.')) {
            $record = getRecord();
            $relationships = explode('.', $this->relationship);
            $value = $record;
            foreach ($relationships as $item) {
                $value = $value?->{$item};
                $model = ($model ?? $record)->{$item}()->getRelated();
            }
            $this->serverSide = get_class($model);
            $this->value = $value?->id;
        } else {
            $model = getRecord()->{$this->relationship}()->getRelated();
            if (
                getRecord()->{$this->relationship}() instanceof \Illuminate\Database\Eloquent\Relations\MorphToMany ||
                getRecord()->{$this->relationship}() instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany
            ) {
                $this->value = getRecord()->{$this->relationship}->pluck('id')->implode(',');
            } else if (getRecord()->{$this->relationship}() instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo) {
                $this->value = getRecord($this->getName());
            }

            $this->serverSide = get_class($model);
        }
        return $this;
    }

    public function modifyQuery(\Closure $cb)
    {
        $this->modifyQuery = $cb;
        return $this;
    }

    public function getModifyQuery()
    {
        return $this->modifyQuery;
    }

    /**
     * limit of record shown, max 100
     * @param int $limit
     */
    public function limit(int $limit)
    {
        $this->limit = $limit;
        if ($limit > 100) $this->limit = 100;
        return $this;
    }

    public function dependValue(string|null $dependValue)
    {
        $this->dependValue = $dependValue;
        return $this;
    }

    public function columnLabel(string|null $columnLabel)
    {
        $this->columnLabel = $columnLabel;
        return $this;
    }

    public function columnValue(string|null $columnValue)
    {
        $this->columnValue = $columnValue;
        return $this;
    }

    public function depend(bool $depend = true)
    {
        $this->depend = $depend;
        return $this;
    }

    public function dependColumn(string|null $dependColumn)
    {
        $this->dependColumn = $dependColumn;
        return $this;
    }

    public function serverSide(string $model)
    {
        $this->serverSide = $model;
        return $this;
    }

    public function options($options)
    {
        $this->options = $options;
        return $this;
    }

    public function multiple(bool $multiple = true)
    {
        $this->multiple = $multiple;
        return $this;
    }

    public function searchable(bool $searchable = true)
    {
        $this->searchable = $searchable;
        return $this;
    }

    public function view()
    {
        if ($this->modifyQuery) {
            if (method_exists($this, 'getModule')) {
                $this->modifyQuery = $this->getModule(). '@'. $this->name;
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
