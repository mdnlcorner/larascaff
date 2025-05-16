<?php

namespace Mulaidarinull\Larascaff\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Action
{
    protected array $options = [];

    public function permission(string $permission): static
    {
        $this->options['permission'] = $permission;

        return $this;
    }

    public function show(\Closure | bool $show): static
    {
        $this->options['show'] = is_bool($show) ? fn () => $show : $show;

        return $this;
    }

    public function form(\Closure | array $form): static
    {

        return $this;
    }

    public function action(): static
    {

        return $this;
    }

    public function color(string | \Mulaidarinull\Larascaff\Enums\ColorVariant $color): static
    {
        if ($color instanceof \Mulaidarinull\Larascaff\Enums\ColorVariant) {
            $color = $color->value;
        }
        $this->options['color'] = $color;

        return $this;
    }

    public function icon(string $icon): static
    {
        $this->options['icon'] = $icon;

        return $this;
    }

    public function label(string $label): static
    {
        $this->options['label'] = $label;

        return $this;
    }

    public function path(string $path): static
    {
        $this->options['path'] = str($path)->start('/')->value();

        return $this;
    }

    public function blank(?bool $blank = true): static
    {
        $this->options['blank'] = $blank ? '_blank' : null;

        return $this;
    }

    public function method(?string $method = 'get'): static
    {
        $this->options['method'] = $method;

        return $this;
    }

    public function ajax(?bool $ajax = true): static
    {
        $this->options['ajax'] = $ajax;

        return $this;
    }

    public function getOptions(): array
    {
        $this->options['permission'] = Arr::get($this->options, 'permission', null);
        $this->options['blank'] = Arr::get($this->options, 'blank', null);
        $this->options['ajax'] = Arr::get($this->options, 'ajax', true);
        $this->options['path'] = Arr::get($this->options, 'path', null);
        $this->options['show'] = Arr::get($this->options, 'show', fn () => fn () => true);
        $this->options['method'] = Arr::get($this->options, 'method', 'get');
        $this->options['icon'] = Arr::get($this->options, 'icon', ($this->options['permission'] == 'update' ? 'tabler-edit' : ($this->options['permission'] == 'read' ? 'tabler-eye' : ($this->options['permission'] == 'delete' ? 'tabler-trash' : null))));
        $this->options['color'] = Arr::get($this->options, 'color', ($this->options['permission'] == 'update' ? 'warning' : ($this->options['permission'] == 'delete' ? 'danger' : 'primary')));

        return $this->options;
    }

    public static function make(string $name): static
    {
        $instance = new static;
        $instance->options['label'] = str($name)->headline()->value();
        $instance->options['name'] = $name;

        return $instance;
    }

    public function actionHandler(Request $request)
    {
        $request->validate(['module' => 'required', 'method' => 'required']);
        if (! class_exists($request->module)) {
            return responseError('Class does not exist');
        }

        return $request->module::{$request->method}($request);
    }
}
