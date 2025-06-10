<?php

namespace Mulaidarinull\Larascaff\Tables;

use Closure;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mulaidarinull\Larascaff\Tables\Columns\Column;
use Yajra\DataTables\Services\DataTable;

class Table extends DataTable
{
    public QueryBuilder | Model | null $query = null;

    public ?EloquentTable $eloquentTable = null;

    protected Collection | array $tableActions = [];

    public function __construct(protected Model | QueryBuilder $model, protected string $url, protected ?string $actionHandler = null)
    {
        $this->query = $model;
        $this->url = $url;
        $this->actionHandler = $actionHandler;
        $this->generateTable();
    }

    public function dataTable(): EloquentTable
    {
        $this->generateTable();

        return $this->eloquentTable;
    }

    public function rowClass(Closure | string | null $class = null): static
    {
        $this->eloquentTable->setRowClass($class);

        return $this;
    }

    public function rowAttr(array $attr): static
    {
        $this->eloquentTable->setRowAttr($attr);

        return $this;
    }

    public function rawColumns(array $columns): static
    {
        $this->eloquentTable->rawColumns($columns);

        return $this;
    }

    public function getActionHandler(): string
    {
        return $this->actionHandler;
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

    /**
     * @param  \Mulaidarinull\Larascaff\Actions\Action[]  $actions
     */
    public function actions(array $actions): static
    {
        foreach ($actions as $action) {
            foreach ($action->getOptions() as $key => $value) {
                $this->tableActions[$key] = $value;
            }
        }
        $this->tableActions = collect($this->tableActions)
            ->map(function ($item) {
                $item['url'] = url($this->url . $item['path']);
                $item['handler'] = [
                    'actionHandler' => $this->actionHandler,
                    'actionName' => $item['name'],
                    'actionType' => $item['hasForm'] === true ? 'form' : 'action',
                    'hasConfirmation' => $item['hasConfirmation'],
                    'id' => null,
                ];

                return $item;
            })
            ->filter(function ($item) {
                if (! user()) {
                    return true;
                }

                return user()->can($item['permission'] . ' ' . $this->url);
            });

        return $this;
    }

    public function getActions()
    {
        return $this->tableActions;
    }

    protected function generateTable()
    {
        if (! $this->eloquentTable) {
            $this->eloquentTable = (new EloquentTable($this->query))->addIndexColumn()
                ->addColumn('action', function (Model $model) {
                    $actions = [];
                    foreach ($this->getActions() as $action) {
                        if ($action['show']($model)) {
                            $action['url'] = str_replace('{{id}}', $model->{$model->getRouteKeyName()}, $action['url']);
                            $action['handler']['id'] = $model->{$model->getRouteKeyName()};
                            $action['handler'] = json_encode($action['handler']);
                            $actions[] = $action;
                        }
                    }

                    return view('larascaff::action', ['actions' => $actions]);
                });
        }
    }

    public function query(?callable $cb = null): QueryBuilder | static
    {
        if (is_callable($cb)) {
            $cb($this->query);

            return $this;
        }

        return $this->query->newQuery();
    }

    private function generateHtmlBuilder(): HtmlBuilder
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

    public function columns(array $columns, bool $hasAction = true, bool $hasIndex = true): static
    {
        $model = explode('Models\\', get_class($this->query->getModel()));
        $this->htmlBuilder = $this->generateHtmlBuilder()->setTableId(strtolower((str_replace('\\', '_', array_pop($model)))) . '-table');

        /** @var Column $column */
        foreach ($columns as $column) {
            if ($state = $column->getState()) {
                $this->eloquentTable->editColumn($column['data'], $state);
            }
        }

        if ($hasIndex) {
            array_unshift(
                $columns,
                Column::make('DT_RowIndex')->title('#')->orderable(false)->searchable(false),
                Column::make('id')->searchable(false)->orderable(true)->hidden(),
            );
        }

        if ($hasAction) {
            array_push($columns, Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'));
        }

        $this->htmlBuilder->columns($columns);

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
        return '_' . date('YmdHis');
    }
}
