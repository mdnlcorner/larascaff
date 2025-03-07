<?php

namespace Mulaidarinull\Larascaff\Components\Forms;

class FormBuilder
{
    protected $view = null;

    protected $formConfig = [
        'size' => 'md',
        'title' => 'Modal title',
        'action' => null,
        'actionLabel' => 'Save',
        'method' => 'POST',
    ];

    public static function make($view)
    {
        $static = new static;
        $static->view = $view;

        return $static;
    }

    public function config(array $config)
    {
        $this->formConfig = $config;

        return $this;
    }

    public function actionLabel($actionLabel)
    {
        $this->formConfig['actionLabel'] = $actionLabel;

        return $this;
    }

    public function method($method)
    {
        $this->formConfig['method'] = $method;

        return $this;
    }

    public function size($size)
    {
        $this->formConfig['size'] = $size;

        return $this;
    }

    public function action($action)
    {
        $this->formConfig['action'] = $action;

        return $this;
    }

    public function title($title)
    {
        $this->formConfig['title'] = $title;

        return $this;
    }

    public function render()
    {
        return view('larascaff::form', ['slot' => $this->view, ...$this->formConfig]);
    }
}
