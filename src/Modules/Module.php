<?php

namespace Mulaidarinull\Larascaff\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Pluralizer;
use Mulaidarinull\Larascaff\Actions\Action;
use Mulaidarinull\Larascaff\Actions\CreateAction;
use Mulaidarinull\Larascaff\Forms\Components\Form;
use Mulaidarinull\Larascaff\Info\Components\Info;
use Mulaidarinull\Larascaff\Tables\Components\Tab;
use Mulaidarinull\Larascaff\Tables\Table;
use Mulaidarinull\Larascaff\Traits\HasMenuPermission;
use Mulaidarinull\Larascaff\Traits\HasPermission;
use Mulaidarinull\Larascaff\Widgets\StatWidget;

abstract class Module extends Controller
{
    use HasMenuPermission;
    use HasPermission;

    protected static ?string $model = null;

    protected static Model | Builder | null $instanceModel = null;

    protected static ?string $url = null;

    protected static ?string $pageTitle = null;

    private static ?Table $datatable = null;

    private array $pageData = [];

    final const NAMESPACE = 'App\\Larascaff\\Modules\\';

    public static function routes(): array
    {
        return [];
    }

    public static function table(Table $table): Table
    {
        return $table;
    }

    public static function infoList(Info $info): Info
    {
        return $info;
    }

    public static function formBuilder(Form $form): Form
    {
        return $form;
    }

    /**
     * @return list<Action>
     */
    public static function actions(): array
    {
        return [];
    }

    /**
     * @return list<class-string<Widget>>
     */
    public static function widgets(): array
    {
        return [];
    }

    public static function getModel(): string
    {
        return static::$model ?? str(static::class)
            ->after(static::NAMESPACE)
            ->beforeLast('Module')
            ->prepend('App\\Models')
            ->toString();
    }

    public static function getInstanceModel(): Model | Builder
    {
        if (! static::$instanceModel) {
            $model = static::getModel();
            static::$instanceModel = new $model;
        }

        return static::$instanceModel;
    }

    public static function makeMenu()
    {
        return static::makeMenuHandler();
    }

    public static function getPageTitle()
    {
        $title = static::$pageTitle;
        if (! $title) {
            $segments = explode('/', static::getUrl());
            if (count($segments)) {
                $title = ucwords(str_replace('-', ' ', array_pop($segments)));
            } else {
                $title = '';
            }
        }

        return $title;
    }

    public static function getActions(bool $validatePermission = false): \Illuminate\Support\Collection
    {
        $url = static::getUrl();
        $actions = [];
        foreach (CreateAction::make()->getOptions() as $key => $create) {
            $actions[$key] = $create;
        }
        foreach (static::actions() as $action) {
            foreach ($action->getOptions() as $key => $value) {
                $actions[$key] = $value;
            }
        }

        $actions = collect($actions)
            ->map(function ($item) use ($url) {
                $item['url'] = url($url . $item['path']);
                $item['handler'] = [
                    'actionHandler' => static::class,
                    'actionType' => $item['hasForm'] === true ? 'form' : 'action',
                    'actionName' => $item['name'],
                    'hasConfirmation' => $item['hasConfirmation'],
                    'id' => null,
                ];

                return $item;
            });

        if ($validatePermission) {
            $actions->filter(function ($action) use ($url) {
                return user()->can($action['permission'] . ' ' . $url);
            });
        }

        return $actions;
    }

    public function index()
    {
        $this->pageData = [
            'pageTitle' => static::getPageTitle(),
            'url' => Pluralizer::singular(static::getUrl()),
            'actions' => static::getActions(true),
        ];

        $this->initializeTable();

        $this->resolveTableFilters();

        $this->resolveTableTabs();

        $this->resolveWidgets();

        return static::$datatable->render('larascaff::main-content', $this->pageData);
    }

    public static function getTableQuery(): Builder
    {
        $query = static::$datatable->getQuery();

        return $query->newQuery();
    }

    protected function initializeTable()
    {
        static::$datatable = new Table(static::getInstanceModel()->newQuery(), static::getUrl(), static::class);
        $this->pageData['tableActions'] = static::getTableActions();
    }

    protected function resolveWidgets()
    {
        $widgets = $this->widgets();

        if (! count($widgets)) {
            return null;
        }

        $this->pageData['widgets'] = view('larascaff::widget-module', [
            'widgets' => $widgets,
        ]);
    }

    protected function resolveTableFilters()
    {
        $filterTable = static::$datatable->resolveTableFilters();

        if ($filterTable == '') {
            return;
        }

        $this->pageData['filterTable'] = view('larascaff::filter-table', [
            'filterTable' => $filterTable,
        ]);
    }

    protected function resolveTableTabs()
    {
        $this->pageData['tabs'] = static::$datatable->getTabs();

        function defaultActiveTab(Tab $tab, Builder $datatable)
        {
            if (is_callable($tab->getQuery())) {
                call_user_func($tab->getQuery(), $datatable);
            }
        }

        if ($this->pageData['tabs']->count()) {
            if (! request()->has('activeTab')) {
                defaultActiveTab($this->pageData['tabs']->first(), static::$datatable->getQuery());
            } else {
                $tab = $this->pageData['tabs'][request()->get('activeTab')] ?? null;
                if ($tab) {
                    if (is_callable($tab->getQuery())) {
                        call_user_func($tab->getQuery(), static::$datatable->getQuery());
                    }
                } else {
                    defaultActiveTab($this->pageData['tabs']->first(), static::$datatable->getQuery());
                }
            }
        }
    }

    public static function getTableActions()
    {
        if (! static::$datatable) {
            static::$datatable = new Table(static::getInstanceModel()->newQuery(), static::getUrl(), static::class);
        }

        return call_user_func_array([static::class, 'table'], [static::$datatable])->getActions();
    }

    public function getRecord($id): Model
    {
        static::$instanceModel = static::getInstanceModel()->query()->where(static::getInstanceModel()->getRouteKeyName(), $id)->firstOrFail();

        return static::$instanceModel;
    }

    public function form($view, array $formConfig = [])
    {
        return view('larascaff::form', ['slot' => $view, ...$formConfig]);
    }

    public static function getUrl(): string
    {
        $url = static::$url;
        if (! $url) {
            $url = str(static::class)->after(static::NAMESPACE)->beforeLast('Module')->explode('\\')
                ->map(fn ($item) => str($item)->kebab())
                ->implode('/');
            $url = Pluralizer::plural($url);
        }

        return str(getPrefix())->finish('/') . $url;
    }

    public static function registerRoutes()
    {
        $routeName = explode('/', static::getUrl());

        $implodeRouteName = (implode('.', $routeName)) . '.';

        foreach (static::routes() as $route) {
            $url = static::getUrl() . (str_starts_with($route['url'], '/') ? $route['url'] : '/' . $route['url']);
            $action = is_string($route['action']) ? [static::class, $route['action']] : $route['action'];
            Route::{$route['method'] ?? 'get'}($url, $action)->name($route['name'] ? $implodeRouteName . $route['name'] : null);
        }

        $lastRouteName = array_pop($routeName);
        Route::name(implode('.', $routeName) . (count($routeName) ? '.' : ''))->group(function () use ($lastRouteName) {
            Route::get(static::getUrl(), [static::class, 'index'])->name($lastRouteName . '.index');
        });
    }

    public static function makeRoute($url, string | \Closure | array | null $action = null, $method = 'get', $name = null)
    {
        return compact('method', 'action', 'url', 'name');
    }
}
