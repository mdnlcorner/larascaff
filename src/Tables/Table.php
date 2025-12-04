<?php

namespace Mulaidarinull\Larascaff\Tables;

use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Mulaidarinull\Larascaff\Contracts\HasColor;
use Mulaidarinull\Larascaff\Contracts\HasIcon;
use Mulaidarinull\Larascaff\Contracts\HasLabel;
use Mulaidarinull\Larascaff\Enums\ColorVariant;
use Mulaidarinull\Larascaff\Forms\Components\DatepickerRange;
use Mulaidarinull\Larascaff\Info\Components\Icon;
use Mulaidarinull\Larascaff\Tables\Columns\Column;
use Mulaidarinull\Larascaff\Tables\Columns\DateColumn;
use Mulaidarinull\Larascaff\Tables\Columns\IconColumn;
use Mulaidarinull\Larascaff\Tables\Columns\ImageColumn;
use Mulaidarinull\Larascaff\Tables\Components\Tab;
use Mulaidarinull\Larascaff\Tables\Enums\ActionsPosition;
use Mulaidarinull\Larascaff\Tables\Filters\DatepickerRangeFilter;
use Mulaidarinull\Larascaff\Tables\Filters\Filter;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;

class Table extends DataTable
{
    protected EloquentBuilder | Model | null $query = null;

    protected ?EloquentTable $eloquentTable = null;

    protected Collection | array $tableActions = [];

    protected ActionsPosition $actionsPosition = ActionsPosition::AfterColumns;

    /** @var Collection<int, Filter> */
    protected Collection $filters;

    /** @var Collection<int, Tab> */
    protected Collection $tabs;

    /** @var class-string<Model> */
    protected $model = null;

    protected bool $isWidget = false;

    public function __construct(Model | EloquentBuilder $model, protected string $url, protected ?string $actionHandler = null)
    {
        $this->model = get_class($model);

        $this->query = $model;

        $this->url = $url;

        $this->actionHandler = $actionHandler;

        $this->generateTable();
    }

    public function widget(bool $status = true): static
    {
        $this->isWidget = $status;

        return $this;
    }

    public function dataTable(): EloquentTable
    {
        $this->generateTable();

        return $this->eloquentTable;
    }

    /**
     * Sets DT_RowClass template.
     */
    public function rowClass(Closure | string | null $class = null): static
    {
        $this->eloquentTable->setRowClass($class);

        return $this;
    }

    /**
     * Set DT_RowAttr templates.
     */
    public function rowAttr(array $attr): static
    {
        $this->eloquentTable->setRowAttr($attr);

        return $this;
    }

    /**
     * Set columns that should not be escaped.
     */
    public function rawColumns(array $columns): static
    {
        $this->eloquentTable->rawColumns($columns);

        return $this;
    }

    public function getActionHandler(): string
    {
        return $this->actionHandler;
    }

    public function resolveTableFilters(): string
    {
        $tableId = $this->htmlBuilder->getTableId();

        $filterTable = '';

        foreach ($this->getFilters() as $filter) {
            $filled = false;

            if ($filter instanceof DatepickerRange) {
                $filled = request()->filled($filter->getName(0)) && request()->filled($filter->getName(1));
            } else {
                $filled = request()->filled($filter->getName());
            }

            if ($filled && $query = $filter->getQuery()) {
                $this->resolveClosureParams($query);
            }

            $filter->attr('data-filter=' . $tableId);
            $filterTable .= $filter->view();
        }

        return $filterTable;
    }

    protected function resolveClosureParams(?callable $cb = null)
    {
        if (!$cb instanceof \Closure) {
            throw new \Exception('Param must be callable');
        }

        $parameters = [];

        $data = [];
        foreach ($this->getFilters() as $filter) {
            if ($filter instanceof DatepickerRangeFilter) {
                $data = array_merge($data, [$filter->getName(0), $filter->getName(1)]);
            } else {
                $data[] = $filter->getName();
            }
        }

        foreach ((new \ReflectionFunction($cb))->getParameters() as $parameter) {
            $default = match ($parameter->getName()) {
                'query' => [$parameter->getName() => $this->query],
                'data' => [$parameter->getName() => request()->only($data)],
                default => []
            };

            $type = match ($parameter->getType()?->getName()) {
                get_class($this->query) => [$parameter->getName() => $this->query],
                EloquentBuilder::class => [$parameter->getName() => $this->query],
                QueryBuilder::class => [$parameter->getName() => $this->query],
                default => []
            };

            $parameters = [...$parameters, ...$default, ...$type];
        }

        return app()->call($cb, $parameters);
    }

    /**
     * @param  list<\Mulaidarinull\Larascaff\Actions\Action>  $actions
     */
    public function actions(array $actions, ActionsPosition $position = ActionsPosition::AfterColumns): static
    {
        $this->tableActions = collect([]);

        foreach ($actions as $action) {
            $options = current($action->getOptions());
            // $options['url'] = url($this->url . $options['path']);
            $options['url'] = url($this->url);

            $confirmation = [];
            if ($options['hasConfirmation']) {
                $confirmation = [
                    'modalTitle' => $options['modalTitle'],
                    'modalDescription' => $options['modalDescription'],
                    'modalIcon' => $options['modalIcon'],
                    'modalSubmitActionLabel' => $options['modalSubmitActionLabel'],
                    'modalCancelActionLabel' => $options['modalCancelActionLabel'],
                ];
            }

            $options['handler'] = [
                'actionHandler' => $this->actionHandler,
                'actionName' => $options['name'],
                'actionType' => $options['hasForm'] === true ? 'form' : 'action',
                'hasConfirmation' => $options['hasConfirmation'],
                'id' => null,
                ...$confirmation,
            ];
            $this->tableActions[$options['name']] = $options;
        }

        $this->tableActions = $this->tableActions->filter(function ($item) {
            if (!user()) {
                return true;
            }

            if ($item['permission']) {
                return user()->can($item['permission'] . ' ' . $this->url);
            }

            return true;
        });

        $this->generateHtmlBuilder();
        $this->actionsPosition = $position;

        $columns = [];
        foreach ($this->htmlBuilder->getColumns() as $column) {
            $columns[] = $column;
        }

        $columnAction = Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->width(60)
            ->addClass('text-center');

        if (!count($columns)) {
            $this->htmlBuilder->add($columnAction);
        } else {
            if ($position->name == 'AfterColumns') {
                $this->htmlBuilder->columns([...$columns, $columnAction]);
            } else {
                $hasIndex = false;
                $columns = array_filter($columns, function ($item) use (&$hasIndex) {
                    if ($item['data'] == 'DT_RowIndex') {
                        $hasIndex = true;
                    }

                    return $item['data'] != 'DT_RowIndex';
                });

                $this->htmlBuilder->columns([...($hasIndex ? [Column::make('DT_RowIndex')->title('#')->orderable(false)->searchable(false)] : []), $columnAction, ...$columns]);
            }
        }

        return $this;
    }

    public function getQuery(): Model | EloquentBuilder | QueryBuilder
    {
        return $this->query;
    }

    public function getActions()
    {
        return $this->tableActions;
    }

    protected function resolvePathClosure($cb, $model)
    {
        $parameters = [];
        foreach ((new \ReflectionFunction($cb))->getParameters() as $parameter) {
            $default = match ($parameter->getName()) {
                'record' => [$parameter->getName() => $model],
                default => []
            };

            $type = match ($parameter->getType()?->getName()) {
                get_class($model) => [$parameter->getType()->getName() => $model],
                default => []
            };

            $parameters = [...$parameters, ...$type,  ...$default];
        }

        return app()->call($cb, $parameters);
    }

    protected function generateTable()
    {
        if (!$this->eloquentTable) {
            $this->eloquentTable = (new EloquentTable($this->query))
                ->addColumn('action', function (Model $model) {
                    $actions = [];
                    foreach ($this->getActions() as $action) {
                        if ($action['show']($model)) {
                            if ($action['path']) {
                                $action['url'] = $this->resolvePathClosure($action['path'], $model);
                            }
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

    /**
     * @param  array<string, Tab>  $tabs
     */
    public function tabs(array $tabs): static
    {
        $this->tabs = collect($tabs);

        return $this;
    }

    /**
     * @return Collection<string, Tab>
     */
    public function getTabs(): Collection
    {
        return $this->tabs ??= collect([]);
    }

    public function filters(array $filters): static
    {
        $this->filters = collect($filters);

        return $this;
    }

    /**
     * @return Collection<int, Filter>
     */
    public function getFilters(): Collection
    {
        return $this->filters ??= collect([]);
    }

    public function query(callable | EloquentBuilder | null $cb = null): EloquentBuilder | static
    {
        if (is_callable($cb)) {
            $cb($this->query);

            return $this;
        } elseif ($cb instanceof Builder) {
            $this->query = $cb;

            return $this;
        }

        return $this->query->newQuery();
    }

    protected function generateHtmlBuilder()
    {
        if (!$this->htmlBuilder) {
            $tableId = str(get_class($this->query->getModel()))->afterLast('App\\Models\\')->replace('\\', '_')->lower()->append('-table')->toString();
            $this->htmlBuilder = $this->builder()
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
                ->searchDelay(800)
                ->minifiedAjax($this->isWidget ? url($this->url . '?tableId=' . $tableId) : '')
                ->selectStyleSingle()
                ->orderBy(1, 'desc')
                ->setTableId($tableId);
        }
    }

    /**
     * @return array<string, array>
     */
    protected function resolveEnumFieldForRawColumns(): array
    {
        $rawColumns = [];

        foreach ($this->eloquentTable->getQuery()->getModel()->getCasts() as $fieldName => $casts) {
            if (enum_exists($casts)) {
                foreach ((new \ReflectionEnum($casts::Closed))->getInterfaces() as $interface => $interfaceReflection) {
                    if ($interface == HasLabel::class) {
                        $rawColumns[$fieldName]['label'] = HasLabel::class;
                    }
                    if ($interface == HasColor::class) {
                        $rawColumns[$fieldName]['color'] = HasColor::class;
                    }
                }
            }
        }

        return $rawColumns;
    }

    protected function makeColumnAsEditColumnFromRawColumns(array $rawColumns)
    {
        foreach ($rawColumns as $field => $actionTypes) {
            $this->eloquentTable->editColumn($field, function ($record) use ($actionTypes, $field) {
                foreach ($actionTypes as $type => $actionType) {
                    // define label
                    if ($actionType === HasLabel::class) {
                        $label = $record->{$field}->getLabel();
                    }

                    // define icon
                    if ($type == 'icon' && $actionType === HasIcon::class) {
                        $icon = $record->{$field}->getIcon();
                    }

                    // define color
                    if ($type == 'color') {
                        if ($actionType === HasColor::class) {
                            $color = $record->{$field}->getColor();
                        } elseif ($actionType instanceof Closure) {
                            $color = $actionType($record);
                            if ($color instanceof ColorVariant) {
                                $color = $color->value;
                            }
                        } elseif (is_string($actionType)) {
                            $color = $actionType;
                        } elseif ($actionType instanceof ColorVariant) {
                            $color = $actionType->value;
                        }
                    }

                    // define badge
                    if ($type === 'badge' && $actionType) {
                        $hasBadge = true;
                    }

                    // override label when action type is closure
                    if ($type == 'closure' && $actionType instanceof Closure) {
                        $label = $actionType($record);
                    }

                    // define icon column
                    if ($type == 'iconColumn') {
                        if ($actionType->isBoolean() || is_bool($record->{$field})) {
                            $label = Icon::make('close')
                                ->label(null)
                                ->boolean()
                                ->value($record->{$field})
                                ->view();
                        } else {
                            $label = $record->{$field};
                        }
                    }

                    // define image column
                    if ($type == 'imageColumn') {
                        $actionType->record($record);
                        $label = $actionType->view();
                    }

                    // define date column
                    if ($type == 'dateColumn') {
                        if ($record->{$field} instanceof Carbon) {
                            $label = $record->{$field}->format($actionType->getFormat());
                        } else {
                            $label = is_null($record->{$field}) ? null : date($actionType->getFormat(), strtotime($record->{$field}));
                        }
                    }

                    if (!isset($label)) {
                        $label = $record->{$field};
                    }
                }

                if (isset($color)) {
                    $html = '<span class="' . 'text-' . $color . '">' . $label . '</span>';
                }

                if (isset($hasBadge)) {
                    if (!isset($color)) {
                        $color = 'primary';
                    }
                    $html = '<div class="inline-block px-2 py-1 text-' . $color . ' text-xs font-semibold rounded-md ' . 'bg-' . $color . '/20 border border-' . $color . '">' . $label . '</div>';
                }

                if (isset($html)) {
                    return $html;
                }

                return $label;
            });
        }
    }

    protected function resolveColumnsForRawColumns($columns, &$rawColumns)
    {
        foreach ($columns as $column) {
            // define badge
            if (isset($column['badge']) && $column['badge'] === true) {
                $rawColumns[$column['data']]['badge'] = $column['badge'];
            }

            // define color
            if (isset($column['color'])) {
                $rawColumns[$column['data']]['color'] = $column['color'];
            }

            // define column editing
            foreach ($column->getColumnEditing() as $actionName => $closureAction) {
                if ($actionName == 'rawColumns') {
                    $rawColumns[$column['data']]['rawColumns'] = 'rawColumns';
                } else {
                    $rawColumns[$column['data']]['closure'] = $closureAction;
                }
            }

            // define icon column
            if ($column instanceof IconColumn) {
                $rawColumns[$column['data']]['iconColumn'] = $column;
            }

            // define image column
            if ($column instanceof ImageColumn) {
                $rawColumns[$column['data']]['imageColumn'] = $column;
            }

            // define date column
            if ($column instanceof DateColumn) {
                $rawColumns[$column['data']]['dateColumn'] = $column;
            }
        }
    }

    /**
     * @param  list<Column>  $columns
     */
    public function columns(array $columns, bool $hasIndex = true): static
    {
        $this->generateHtmlBuilder();

        $rawColumns = $this->resolveEnumFieldForRawColumns();

        $this->resolveColumnsForRawColumns($columns, $rawColumns);

        $this->makeColumnAsEditColumnFromRawColumns($rawColumns);

        $this->eloquentTable->rawColumns(array_keys($rawColumns) ?? []);

        foreach ($this->htmlBuilder->getColumns() as $builderColumn) {
            $builderColumns[] = $builderColumn;
        }

        $indexColumns = [];
        if ($hasIndex) {
            $this->eloquentTable->addIndexColumn();
            $indexColumns = [
                Column::make('DT_RowIndex')->title('#')->orderable(false)->searchable(false),
                Column::make('id')->searchable(false)->hidden(),
            ];
        }

        if ($this->actionsPosition->name == 'AfterColumns') {
            $columns = [...$indexColumns, ...$columns, ...($builderColumns ?? [])];
        } else {
            $columns = [...$indexColumns, ...($builderColumns ?? []), ...$columns];
        }

        $this->htmlBuilder->columns($columns);

        return $this;
    }

    public function html(): Builder
    {
        return $this->htmlBuilder;
    }

    protected function filename(): string
    {
        return '_' . date('YmdHis');
    }
}
