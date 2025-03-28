<?php

namespace Mulaidarinull\Larascaff\Components\Layouts;

use Illuminate\Support\Facades\Blade;
use Mulaidarinull\Larascaff\Components\Concerns\HasCollapsible;
use Mulaidarinull\Larascaff\Components\Concerns\HasColumnSpan;
use Mulaidarinull\Larascaff\Components\Concerns\HasComponent;
use Mulaidarinull\Larascaff\Components\Concerns\HasRelationship;

class Repeater
{
    use HasCollapsible, HasColumnSpan, HasComponent, HasRelationship;

    protected ?string $description = null;

    protected array $withCalculate = [];

    protected array $tableRows = [];

    protected $handleAddRows = null;

    protected $module = null;

    protected $beforeStore = null;

    public function __construct()
    {
        $this->columnSpan = 'full';
    }

    public function description(string $description)
    {
        $this->description = $description;

        return $this;
    }

    public function withCalculate(array $withCalculate)
    {
        $this->withCalculate = $withCalculate;

        return $this;
    }

    public function handleAddRows(callable $cb, $module)
    {
        $this->handleAddRows = $cb;
        $this->module = $module;

        return $this;
    }

    public function getHandleAddRows()
    {
        return $this->handleAddRows;
    }

    public function tableRows(array $tableRows)
    {
        $this->tableRows = $tableRows;

        return $this;
    }

    public function beforeStore(callable $cb)
    {
        $this->beforeStore = $cb;

        return $this;
    }

    public function getBeforeStore()
    {
        return $this->beforeStore;
    }

    public function view()
    {
        if (! $this->relationship) {
            $this->relationship = $this->name;
        }

        return Blade::render(
            <<<'HTML'
            <x-larascaff::layouts.repeater 
                :name="$name"
                :components="$components"
                :columnSpan="$columnSpan"
                :columns="$columns"
                :description="$description"
                :collapsible="$collapsible"
                :collapsed="$collapsed"
                :withCalculate="$withCalculate"
                :relationship="$relationship"
                :tableRows="$tableRows"
                :module="$module"
            />
            HTML,
            [
                'name' => $this->name,
                'components' => $this->components,
                'columnSpan' => $this->columnSpan,
                'columns' => $this->columns,
                'description' => $this->description,
                'collapsible' => $this->collapsible,
                'collapsed' => $this->collapsed,
                'withCalculate' => $this->withCalculate,
                'relationship' => $this->relationship,
                'tableRows' => $this->tableRows,
                'module' => $this->module,
            ]
        );
    }
}
