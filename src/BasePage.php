<?php

namespace Mulaidarinull\Larascaff;

use App\Http\Controllers\Controller;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Reflector;
use Mulaidarinull\Larascaff\Traits\HasMenuPermission;
use Mulaidarinull\Larascaff\Traits\HasPermission;

abstract class BasePage extends Controller
{
    use HasPermission, HasMenuPermission;
    protected string $view = '';
    protected string $url = '';
    protected string $pageTitle = '';

    public function __construct()
    {
        $this->resolveUrl();
        $this->resolvePageTitle();
        $this->resolveView();
    }

    public static function makeMenu()
    {
        $static = new static;
        $static->handleMakeMenu();
    }

    protected function resolvePageTitle()
    {
        if ($this->pageTitle == '') {
            $segments = explode('/', $this->url);
            if (count($segments)) {
                $this->pageTitle = ucwords(str_replace('-', ' ', array_pop($segments)));
            } else {
                $this->pageTitle = '';
            }
        }
    }

    protected function resolveUrl()
    {
        $prefix = getPrefix();
        if ($prefix) $prefix = $prefix .= '/';
        
        if ($this->url == '') {
            $class = get_class($this);
            $pages = explode('App\\Larascaff\\Pages\\', $class);
            array_shift($pages);

            $this->url = substr($pages[0], 0, strlen($pages[0]) - 4);

            $this->url = implode('/', array_map(function ($item) {
                return \Illuminate\Support\Str::kebab($item);
            }, explode('\\', $this->url)));

            $this->url = Pluralizer::plural($this->url);
        }
        $this->url = $prefix . $this->url;
    }

    protected function resolveView()
    {
        if ($this->view == '') {
            $class = get_class($this);
            $pages = explode('App\\Larascaff\\Pages\\', $class);
            array_shift($pages);
            $pages[count($pages) - 1] = substr($pages[count($pages) - 1], 0, strlen($pages[count($pages) - 1]) - 4);
            $pages = strtolower(implode('.', $pages));
            $this->view = 'pages.' . $pages;
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = [
            'pageTitle' => $this->pageTitle,
            'url' => $this->url,
        ];

        $viewData = [];
        if (method_exists($this, $method = 'viewData')) {
            $parameters = $this->resolveParameters($method, [$request]);
            $viewData = call_user_func_array([$this, $method], $parameters);
        }
        $data['view'] = view($this->view, $viewData);

        if (method_exists($this, $method = 'widgets')) {
            $parameters = $this->resolveParameters($method, []);
            $widgets = call_user_func_array([$this, $method], $parameters);

            $data['widgets'] = view('larascaff::widget', [
                'widgets' => $widgets
            ]);
        }

        return view('larascaff::main-content', $data);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    protected function resolveParameters($method, $excepts = [])
    {
        $this->container ??= new Container;

        $beforeSave = new \ReflectionMethod($this, $method);
        $parameters = [];
        foreach ($beforeSave->getParameters() as $param) {
            $className = Reflector::getParameterClassName($param);
            $found = false;
            foreach ($excepts as $except) {
                if ($className == get_class($except)) {
                    $parameters[] = $except;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $parameters[] = $this->container->make($className);
            }
        }

        return $parameters;
    }
}
