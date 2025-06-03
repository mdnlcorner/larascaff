<?php

namespace Mulaidarinull\Larascaff\Actions;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Mulaidarinull\Larascaff\Forms\Components\Form;
use Mulaidarinull\Larascaff\Forms\Concerns\HasModule;
use Mulaidarinull\Larascaff\Info\Components\Info;

class Action
{
    use Concerns\HasConfirmation;
    use Concerns\HasForm;
    use Concerns\HasLifecycle;
    use Concerns\HasMedia;
    use Concerns\HasRelationship;
    use Concerns\HasValidation;
    use HasModule;

    protected array $options = [];

    protected ?string $permission = null;

    protected ?Closure $show = null;

    protected ?string $color = null;

    protected ?string $name = null;

    protected ?string $label = null;

    protected ?string $icon = null;

    protected bool $ajax = true;

    protected ?string $path = null;

    protected ?string $blank = null;

    protected ?Closure $action = null;

    protected string $method = 'post';

    protected ?string $title = null;

    public static function make(string $name): static
    {
        $static = new static;
        $static->setup($name);

        return $static;
    }

    protected function setup(string $name)
    {
        $this->label = str($name)->headline()->value();
        $this->name = $name;
        if (request()->has(['_action_handler', '_action_name', '_action_type', '_id']) && request()->ajax()) {
            $this->module(request()->post('_action_handler'));
            $this->fillFormData();

            $this->form(function (Form $form) {
                return $this->getModule()::formBuilder($form);
            });
        }
    }

    public function permission(string $permission): static
    {
        $this->permission = $permission;

        return $this;
    }

    public function getPermission(): string
    {
        return $this->permission;
    }

    public function show(\Closure | bool $show): static
    {
        $this->show = is_bool($show) ? fn () => $show : $show;

        return $this;
    }

    public function action(\Closure $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function title(Closure | string $title): static
    {
        if (is_callable($title)) {
            $this->title = $this->resolveClosureParams($title) ?? '';
        }
        $this->title = $title;

        return $this;
    }

    public function color(string | \Mulaidarinull\Larascaff\Enums\ColorVariant $color): static
    {
        if ($color instanceof \Mulaidarinull\Larascaff\Enums\ColorVariant) {
            $color = $color->value;
        }
        $this->color = $color;

        return $this;
    }

    public function icon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function path(string $path): static
    {
        $this->path = str($path)->start('/')->value();

        return $this;
    }

    public function blank(?bool $blank = true): static
    {
        $this->blank = $blank ? '_blank' : null;

        return $this;
    }

    public function method(?string $method = 'get'): static
    {
        $this->method = $method;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function ajax(?bool $ajax = true): static
    {
        $this->ajax = $ajax;

        return $this;
    }

    public function getOptions(): array
    {
        $this->options['permission'] = $this->permission;
        $this->options['blank'] = $this->blank;
        $this->options['ajax'] = $this->ajax;
        $this->options['path'] = $this->path;
        $this->options['show'] = $this->show ?? fn () => true;
        $this->options['method'] = $this->method;
        $this->options['icon'] = $this->icon ?? ($this->permission == 'update' ? 'tabler-edit' : ($this->permission == 'read' ? 'tabler-eye' : ($this->permission == 'delete' ? 'tabler-trash' : null)));
        $this->options['color'] = $this->color ?? ($this->permission == 'update' ? 'warning' : ($this->permission == 'delete' ? 'danger' : 'primary'));
        $this->options['form'] = $this->form;
        $this->options['hasForm'] = $this->hasForm;
        $this->options['name'] = $this->name;
        $this->options['label'] = $this->label;
        $this->options['action'] = $this->action;
        $this->options['hasConfirmation'] = $this->confirmation;
        $this->options['beforeFormFilled'] = $this->beforeFormFilled;

        return [$this->name => $this->options];
    }

    protected function fillFormData()
    {
        $this->formData = request()->except([
            '_token',
            '_method',
            '_action_handler',
            '_action_name',
            '_action_type',
            '_id',
        ]);
    }

    public function actionHandler(Request $request)
    {
        $request->validate([
            '_action_handler' => 'required',
            '_action_name' => 'required',
            '_action_type' => 'required',
        ]);

        if (! class_exists($request->post('_action_handler'))) {
            return responseError('Class does not exist');
        }

        $this->module($request->post('_action_handler'));

        // get actions
        $actions = call_user_func([$request->post('_action_handler'), 'getActions']);
        // get table actions
        $actions = $actions->merge(call_user_func([$request->post('_action_handler'), 'getTableActions']));
        $actions = Arr::get($actions, $request->post('_action_name'), null);

        if (is_null($actions)) {
            return responseError('Action not found');
        }

        $model = $request->post('_action_handler')::getInstanceModel();
        if ($request->post('_id') && $request->post('_id') != 'null') {
            setRecord($model->query()->where($model->getRouteKeyName(), $request->post('_id'))->firstOrFail());
        } else {
            setRecord($model);
        }

        switch ($request->post('_action_type')) {
            case 'form':
                /**
                 * @var Form|Info
                 */
                $form = $this->resolveClosureParams($actions['form']);

                $this->callHook($actions['beforeFormFilled']);

                return response()->json([
                    'action_handler' => $request->post('_action_handler'),
                    'action_name' => $request->post('_action_name'),
                    'action_type' => 'action',
                    'id' => $request->post('_id'),
                    'html' => view('larascaff::form', [
                        'form' => $form,
                        'action' => isset($actions['action']) ? url('handler') : null,
                    ])->render(),
                ]);

                break;
            case 'action':
                if ($actions['form'] && ! $this->form) {
                    $this->fillFormData();
                    $this->form = $actions['form'];
                    $this->inspectFormBuilder($this->getForm()->getComponents());
                }

                return $this->resolveClosureParams($actions['action']);

                break;
        }
    }

    protected function resolveClosureParams(?callable $cb = null)
    {
        if (! $cb instanceof \Closure) {
            return;
        }

        $parameters = [];
        foreach ((new \ReflectionFunction($cb))->getParameters() as $parameter) {
            $default = match ($parameter->getName()) {
                'record' => [$parameter->getName() => getRecord()],
                'model' => [$parameter->getName() => $this->getModule()::getModel()],
                'data' => [$parameter->getName() => $this->getFormData()],
                'form' => [$parameter->getName() => app()->make(Form::class)->module($this->getModule())],
                'info' => [$parameter->getName() => app()->make(Info::class)->module($this->getModule())],
                default => []
            };

            $type = match ($parameter->getType()?->getName()) {
                $this->getModule()::getModel() => [$parameter->getName() => getRecord()],
                Form::class => [$parameter->getName() => Arr::get($default, 'form', app()->make(Form::class)->module($this->getModule()))],
                Info::class => [$parameter->getName() => Arr::get($default, 'info', app()->make(Info::class)->module($this->getModule()))],
                default => []
            };

            $parameters = [...$parameters, ...$default, ...$type];
        }

        return app()->call($cb, $parameters);
    }
}
