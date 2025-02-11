<?php

namespace Mulaidarinull\Larascaff;

use App\Http\Controllers\Controller;
use Illuminate\Container\Container;
use Illuminate\Support\Reflector;
use Mulaidarinull\Larascaff\Traits\HasPermission;

abstract class BasePage extends Controller
{
    use HasPermission;
    protected string $view = '';
    protected string $url = '';
    protected string $pageTitle = '';

    public function __construct()
    {
        $this->resolvePageTitle();
        $this->resolveUrl();
        $this->resolveView();
    }

    protected function resolvePageTitle()
    {
        if ($this->pageTitle == '') {
            $segments = request()->segments();
            if (count($segments)) {
                $this->pageTitle = ucfirst($segments[count($segments) - 1]);
            } else {
                $this->pageTitle = '';
            }
        }
    }

    protected function resolveUrl()
    {
        if ($this->url == '') {
            $prefix = getPrefix();
            if ($prefix) $prefix = $prefix .= '/';

            $class = get_class($this);
            $pages = explode('App\\Larascaff\\Pages\\', $class);
            array_shift($pages);
            $pages[count($pages) - 1] = substr($pages[count($pages) - 1], 0, strlen($pages[count($pages) - 1]) - 4);
            $pages = strtolower(str_replace('\\', '/',implode('-', $pages)));
            
            $this->url = $prefix . $pages;
        }
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
    public function index()
    {
        $data = [
            'pageTitle' => $this->pageTitle,
            'url' => $this->url,
        ];

        $viewData = [];
        if (method_exists($this, $method = 'viewData')) {
            $parameters = $this->resolveParameters($method, []);
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
