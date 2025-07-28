<?php

namespace Mulaidarinull\Larascaff\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Pluralizer;
use Mulaidarinull\Larascaff\Tables\Table;
use Mulaidarinull\Larascaff\Traits\HasMenuPermission;
use Mulaidarinull\Larascaff\Traits\HasPermission;

abstract class Page extends Controller
{
    use HasMenuPermission;
    use HasPermission;

    protected static ?string $view = null;

    protected static ?string $url = null;

    protected static ?string $pageTitle = null;

    private array $pageData = [];

    final const NAMESPACE = 'App\\Larascaff\\Pages\\';

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

    public static function getUrl(): string
    {
        $url = static::$url;
        if (! $url) {
            $url = str(static::class)->after(static::NAMESPACE)->beforeLast('Page')->explode('\\')
                ->map(fn ($item) => str($item)->kebab())
                ->implode('/');
            $url = Pluralizer::plural($url);
        }

        return str(getPrefix())->finish('/') . $url;
    }

    public static function getView()
    {
        $view = static::$view;
        if (! $view) {
            $view = str(static::class)->after(static::NAMESPACE)->beforeLast('Page')->lower()
                ->replace('\\', '.')->prepend('pages.')->toString();
        }

        return $view;
    }

    protected function resolveClosureParams(string $method)
    {
        $parameters = [];
        foreach ((new \ReflectionMethod($this, $method))->getParameters() as $parameter) {
            if (! class_exists($parameter->getType()->getName())) {
                throw new \Exception('Parameter must be class');
            }
            $parameters[$parameter->getName()] = resolve($parameter->getType()->getName());
        }

        return app()->call([$this, $method], $parameters);
    }

    public function index()
    {
        $this->pageData = [
            'pageTitle' => static::getPageTitle(),
            'url' => static::getUrl(),
        ];

        if (method_exists($this, $method = 'viewData')) {
            $viewData = $this->resolveClosureParams($method);
        }
        $this->pageData['view'] = view(static::getView(), $viewData ?? []);

        $widgets = [];
        if (method_exists($this, $method = 'widgets')) {
            $widgets = $this->resolveClosureParams($method);
        }

        $resolveTableWidget = function (string $tableWidget, bool $isAjax = false) {
            $table = new Table($tableWidget::getModel()::query(), static::getUrl(), $tableWidget);
            $table->widget(true);

            call_user_func_array([$tableWidget, 'table'], [$table]);

            if ($isAjax) {
                return $table;
            }

            return $table->html();
        };

        if (request()->ajax() && request()->expectsJson()) {
            foreach ($widgets as $tableWidget) {
                if ($tableWidget::getWidgetType() == 'table') {

                    $table = $resolveTableWidget(tableWidget: $tableWidget, isAjax: true);

                    if ($table->builder()->getTableId() == request()->get('tableId')) {
                        return $table->render('larascaff::widget');
                    }
                }
            }
        }

        $this->pageData['widgets'] = view('larascaff::widget', ['widgets' => $widgets, 'resolveTableWidget' => $resolveTableWidget]);

        return view('larascaff::main-content', $this->pageData);
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

        Route::get(static::getUrl(), [static::class, 'index'])->name(implode('.', explode('/', static::getUrl())));
    }

    public static function routes(): array
    {
        return [];
    }

    public static function makeRoute($url, string | callable | array | null $action = null, $method = 'get', $name = null)
    {
        return compact('method', 'action', 'url', 'name');
    }

    public function pageHandler(Request $request)
    {
        $request->validate(['module' => 'required', 'method' => 'required']);
        if (! class_exists($request->module)) {
            return responseError('Class does not exist');
        }

        return app()->call([app()->make($request->module), $request->method]);
    }
}
