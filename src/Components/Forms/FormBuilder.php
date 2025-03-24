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

    public static function make($view): static
    {
        $static = new static;
        $static->view = $view;

        return $static;
    }

    public function config(array $config): static
    {
        $this->formConfig = $config;

        return $this;
    }

    public function actionLabel($actionLabel): static
    {
        $this->formConfig['actionLabel'] = $actionLabel;

        return $this;
    }

    public function method($method): static
    {
        $this->formConfig['method'] = $method;

        return $this;
    }

    public function size($size): static
    {
        $this->formConfig['size'] = $size;

        return $this;
    }

    public function action($action): static
    {
        $this->formConfig['action'] = $action;

        return $this;
    }

    public function title($title): static
    {
        $this->formConfig['title'] = $title;

        return $this;
    }

    public function render()
    {
        return view('larascaff::form', ['slot' => $this->view, ...$this->formConfig]);
    }
}
