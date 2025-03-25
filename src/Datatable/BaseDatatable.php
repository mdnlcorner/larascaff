<?php

namespace Mulaidarinull\Larascaff\Datatable;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class BaseDatatable extends DataTable
{
    public QueryBuilder|Model|null $query = null;

    public ?EloquentDataTable $eloquentTable = null;

    public function __construct(protected Model|QueryBuilder $model, protected string $url, protected array $tableActions = [])
    {
        $this->model = $this->query = $model;
        $this->url = $url;
        // $this->tableActions = $tableActions;
        $this->tableActions = [];
    }

    public function dataTable(): EloquentDataTable
    {
        $this->generateTable();

        return $this->eloquentTable;
    }

    public function filterTable($filter = []): static
    {
        $this->filterTable = $filter;
        $this->query = $this->query->newQuery();
        foreach ($filter as $item) {
            if (request()->filled($item['name'])) {
                if ($item['type'] == 'nullable') {
                    if (request($item['name']) === '0') {
                        $this->query->whereNull($item['name']);
                    } elseif (request($item['name']) === '1') {
                        $this->query->whereNotNull($item['name']);
                    }
                } else {
                    $this->query->where($item['name'], request($item['name']));
                }
            }
        }

        return $this;
    }

    public function actions(array $actions): static
    {
        $this->tableActions = $actions;

        return $this;
    }

    public function getActions()
    {
        return collect($this->tableActions)->flatMap(fn ($item) => $item)->toArray();
    }

    protected function generateTable()
    {
        if (! $this->eloquentTable) {
            $this->eloquentTable = (new EloquentDataTable($this->query))->addIndexColumn()
                ->addColumn('action', function (Model $model) {
                    $actions = [];
                    foreach ($this->getActions() as $action) {
                        if ($action['show']($model)) {
                            $action['url'] = url($this->url.str_replace('{{id}}', $model->{$model->getRouteKeyName()}, $action['url']));
                            $actions[$action['permission']] = $action;
                        }
                    }

                    return view('larascaff::action', ['actions' => $actions]);
                });
        }
    }

    public function customizeColumn(callable $cb): static
    {
        $this->generateTable();
        $cb($this->eloquentTable);

        return $this;
    }

    public function customQuery($cb): static
    {
        if (is_callable($cb)) {
            $this->query = $this->query->newQuery();
            $cb($this->query);
        } else {
            $this->query = $cb;
        }

        return $this;
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(): QueryBuilder
    {
        return $this->query->newQuery();
    }

    private function generateHtmlBuilder(): \Mulaidarinull\Larascaff\Datatable\HtmlBuilder
    {
        return app(HtmlBuilder::class)
            ->parameters([
                'searchDelay' => 1000,
                'responsive' => [
                    'details' => [
                        'display' => '$.fn.dataTable.Responsive.display.childRowImmediate',
                    ],
                ],

            ])
            ->language(['paginate' => [
                'next' => '→',
                'previous' => '←',
            ]])
            ->minifiedAjax()
            ->selectStyleSingle()
            ->orderBy(1, 'desc');
    }

    public function columns($cb): static
    {
        $model = explode('Models\\', get_class($this->model->getModel()));
        $this->htmlBuilder = $this->generateHtmlBuilder()->setTableId(strtolower((str_replace('\\', '_', array_pop($model)))).'-table');
        $cb($this->htmlBuilder);

        return $this;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->htmlBuilder;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return '_'.date('YmdHis');
    }
}
