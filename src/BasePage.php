<?php

namespace Mulaidarinull\Larascaff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Pluralizer;
use Mulaidarinull\Larascaff\Traits\HasMenuPermission;
use Mulaidarinull\Larascaff\Traits\HasPermission;
use Mulaidarinull\Larascaff\Traits\ParameterResolver;

abstract class BasePage extends Controller
{
    use HasMenuPermission;
    use HasPermission;
    use ParameterResolver;

    protected static ?string $view = null;

    protected static ?string $url = null;

    protected static ?string $pageTitle = null;

    public static function makeMenu()
    {
        return static::handleMakeMenu();
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
            $url = substr(get_called_class(), strlen('App\\Larascaff\\Pages\\'));
            $url = substr($url, 0, strlen($url) - 4);
            $url = implode('/', array_map(function ($item) {
                return \Illuminate\Support\Str::kebab($item);
            }, explode('\\', $url)));
            $url = Pluralizer::plural($url);
        }

        return (getPrefix() ? getPrefix() . '/' : '') . $url;
    }

    public static function getView()
    {
        $view = static::$view;
        if (! $view) {
            $class = get_called_class();
            $pages = explode('App\\Larascaff\\Pages\\', $class);
            array_shift($pages);
            $pages[count($pages) - 1] = substr($pages[count($pages) - 1], 0, strlen($pages[count($pages) - 1]) - 4);
            $pages = strtolower(implode('.', $pages));
            $view = 'pages.' . $pages;
        }

        return $view;
    }

    public function index(Request $request)
    {
        $data = [
            'pageTitle' => static::getPageTitle(),
            'url' => static::getUrl(),
        ];

        $viewData = [];
        if (method_exists($this, $method = 'viewData')) {
            $parameters = $this->resolveParameters($method, [$request]);
            $viewData = call_user_func_array([$this, $method], $parameters);
        }
        $data['view'] = view(static::getView(), $viewData);

        if (method_exists($this, $method = 'widgets')) {
            $parameters = $this->resolveParameters($method, []);
            $widgets = call_user_func_array([$this, $method], $parameters);

            $data['widgets'] = view('larascaff::widget', [
                'widgets' => $widgets,
            ]);
        }

        return view('larascaff::main-content', $data);
    }

    public static function makeRoute($url, string | callable | array | null $action = null, $method = 'get', $name = null)
    {
        return compact('method', 'action', 'url', 'name');
    }
}
