<?php

namespace Mulaidarinull\Larascaff\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Pluralizer;
use Mulaidarinull\Larascaff\Traits\HasMenuPermission;
use Mulaidarinull\Larascaff\Traits\HasPermission;

abstract class Page extends Controller
{
    use HasMenuPermission;
    use HasPermission;

    protected static ?string $view = null;

    protected static ?string $url = null;

    protected static ?string $pageTitle = null;

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
        $data = [
            'pageTitle' => static::getPageTitle(),
            'url' => static::getUrl(),
        ];

        $viewData = [];
        if (method_exists($this, $method = 'viewData')) {
            $viewData = $this->resolveClosureParams($method);
        }
        $data['view'] = view(static::getView(), $viewData);

        $widgets = [];
        if (method_exists($this, $method = 'widgets')) {
            $widgets = $this->resolveClosureParams($method);
        }
        $data['widgets'] = view('larascaff::widget', ['widgets' => $widgets]);

        return view('larascaff::main-content', $data);
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
