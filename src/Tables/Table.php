<?php

namespace Mulaidarinull\Larascaff\Tables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
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
    }

    public function dataTable(): EloquentTable
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

    /**
     * @param  \Mulaidarinull\Larascaff\Actions\Action[]  $actions
     */
    public function actions(array $actions): static
    {
        $this->tableActions = collect($actions)
            ->map(function ($item) {
                $item = $item->getOptions();
                $item['url'] = url($this->url . $item['path']);

                return $item;
            })
            ->filter(function ($item) {
                if (! user()) {
                    return true;
                }

                return user()->can($item['permission'] . ' ' . $this->url);
            })->keyBy('name');

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
                    foreach ($this->tableActions as $action) {
                        if ($action['show']($model)) {
                            $action['url'] = str_replace('{{id}}', $model->{$model->getRouteKeyName()}, $action['url']);
                            $action['handler'] = json_encode([
                                'actionHandler' => $this->actionHandler,
                                'actionName' => $action['name'],
                                'actionType' => 'form',
                                'id' => $model->{$model->getRouteKeyName()},
                            ]);
                            $actions[] = $action;
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

    public function columns($cb): static
    {
        $model = explode('Models\\', get_class($this->query->getModel()));
        $this->htmlBuilder = $this->generateHtmlBuilder()->setTableId(strtolower((str_replace('\\', '_', array_pop($model)))) . '-table');
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
        return '_' . date('YmdHis');
    }
}
