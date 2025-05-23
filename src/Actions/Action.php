<?php

namespace Mulaidarinull\Larascaff\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Mulaidarinull\Larascaff\Forms\Components\Form;
use Mulaidarinull\Larascaff\Forms\Concerns\HasModule;

class Action
{
    use Concerns\HasForm;
    use Concerns\HasLifecycle;
    use Concerns\HasMedia;
    use Concerns\HasValidation;
    use HasModule;

    protected array $options = [];

    public static function make(string $name): static
    {
        $static = new static;
        $static->setup($name);

        return $static;
    }

    protected function setup(string $name)
    {
        $this->options['label'] = str($name)->headline()->value();
        $this->options['name'] = $name;
        if (request()->has(['_action_handler', '_action_name', '_action_type', '_id']) && request()->ajax()) {
            $this->module(request()->post('_action_handler'));
            $this->formData = request()->except([
                '_token',
                '_method',
                '_action_handler',
                '_action_name',
                '_action_type',
                '_id',
            ]);

            $this->form(function (Form $form) {
                return $this->getModule()::formBuilder($form);
            });
        }
    }

    public function permission(string $permission): static
    {
        $this->options['permission'] = $permission;

        return $this;
    }

    public function getPermission()
    {
        return $this->options['permission'];
    }

    public function show(\Closure | bool $show): static
    {
        $this->options['show'] = is_bool($show) ? fn () => $show : $show;

        return $this;
    }

    public function action(\Closure $action): static
    {
        $this->options['action'] = $action;

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
        $this->options['form'] = Arr::get($this->options, 'form', null);
        $this->options['name'] = $this->options['name'];

        return $this->options;
    }

    public function actionHandler(Request $request)
    {
        $request->validate([
            '_action_handler' => 'required',
            '_action_name' => 'required',
            '_action_type' => 'required',
        ]);

        $this->module($request->post('_action_handler'));

        if (! class_exists($request->post('_action_handler'))) {
            return responseError('Class does not exist');
        }

        $handler = call_user_func([$request->post('_action_handler'), 'getTableActions']);
        $handler = Arr::get($handler, $request->post('_action_name'), null);

        if (is_null($handler)) {
            return responseError('Action not found');
        }

        if ($request->post('_id')) {
            $model = $request->post('_action_handler')::getInstanceModel();
            setRecord($model->query()->where($model->getRouteKeyName(), $request->post('_id'))->firstOrFail());
        }

        switch ($request->post('_action_type')) {
            case 'form':
                $form = $this->resolveClosureParams($handler['form']);

                return response()->json([
                    'action_handler' => $request->post('_action_handler'),
                    'action_name' => $request->post('_action_name'),
                    'action_type' => 'action',
                    'id' => $request->post('_id'),
                    'html' => view('larascaff::form', [
                        'slot' => view('larascaff::form-builder', ['form' => $form]),
                        'size' => $request->post('_action_handler')::getModalSize(),
                        'title' => $request->post('_action_handler')::getModalTitle(),
                        'action' => url('larascaff'),
                        'method' => 'POST',
                    ])->render(),
                ]);

                break;
            default:
                return $this->resolveClosureParams($handler['action']);

                break;
        }
    }

    protected function resolveClosureParams(callable $cb)
    {
        $parameters = [];
        foreach ((new \ReflectionFunction($cb))->getParameters() as $parameter) {
            $default = match ($parameter->getName()) {
                'record' => ['record' => getRecord()],
                'model' => ['model' => $this->getModule()::getModel()],
                'data' => ['data' => $this->getFormData()],
                default => []
            };

            $type = match ($parameter->getType()?->getName()) {
                $this->getModule()::getModel() => [$parameter->getName() => getRecord()],
                default => []
            };

            $parameters = [...$parameters, ...$default, ...$type];
        }

        return app()->call($cb, $parameters);
    }
}
