<?php

namespace Mulaidarinull\Larascaff\Actions;

use BackedEnum;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Mulaidarinull\Larascaff\Forms\Components\Form;
use Mulaidarinull\Larascaff\Forms\Concerns\HasModule;
use Mulaidarinull\Larascaff\Info\Components\Info;

class Action
{
    use Concerns\HasConfirmation;
    use Concerns\HasForm;
    use Concerns\HasLifecycle;
    use Concerns\HasMedia;
    use Concerns\HasModal;
    use Concerns\HasNotification;
    use Concerns\HasPermission;
    use Concerns\HasRelationship;
    use Concerns\HasValidation;
    use HasModule;

    protected array $options = [];

    protected ?Closure $show = null;

    protected string $color = 'primary';

    protected ?string $name = null;

    protected ?string $label = null;

    protected ?string $icon = null;

    protected bool $ajax = true;

    protected ?string $path = null;

    protected ?string $blank = null;

    protected ?Closure $action = null;

    protected bool $isCustomAction = false;

    protected string $method = 'post';

    protected ?string $title = null;

    protected ?Model $replica = null;

    public static function make(?string $name = 'action'): static
    {
        $static = app(static::class);
        $static->setup($name);

        return $static;
    }

    public function getInstance(): static
    {
        return $this;
    }

    protected function setup(string $name)
    {
        $this->name = $name;

        $this->label(str($name)->headline()->value());

        $this->modalSubmitActionLabel('Save');

        $this->modalCancelActionLabel('Cancel');

        $this->modalCancelActionLabel(__('larascaff::action.modal.cancel.title'));

        $this->permission(false);

        if (request()->has(['_action_handler', '_action_name', '_action_type', '_id']) && request()->ajax()) {
            $this->module(request()->post('_action_handler'));
            $this->fillFormData();

            $this->form(function (Form $form) {
                return $this->getModule()::formBuilder($form);
            });
        }
    }

    public function show(Closure | bool $show): static
    {
        $this->show = is_bool($show) ? fn () => $show : $show;

        return $this;
    }

    public function action(Closure $action): static
    {
        $this->action = $action;
        $this->isCustomAction = true;

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
        if ($color instanceof BackedEnum) {
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
        // $this->options['instance'] = $this;
        $this->options['permission'] = $this->permission;
        $this->options['blank'] = $this->blank;
        $this->options['ajax'] = $this->ajax;
        $this->options['path'] = $this->path;
        $this->options['show'] = $this->show ?? fn () => true;
        $this->options['method'] = $this->method;
        $this->options['icon'] = $this->icon;
        $this->options['color'] = $this->color;
        $this->options['form'] = $this->form;
        $this->options['hasForm'] = $this->hasForm;
        $this->options['name'] = $this->name;
        $this->options['label'] = $this->label;
        $this->options['action'] = $this->action;
        $this->options['isCustomAction'] = $this->isCustomAction;
        $this->options['hasConfirmation'] = $this->hasConfirmation();
        $this->options['withValidations'] = $this->withValidations;
        $this->options['beforeFormFilled'] = $this->beforeFormFilled;
        $this->options['notification'] = $this->getNotification();
        $this->options['modalTitle'] = $this->modalTitle;
        $this->options['modalSubmitActionLabel'] = $this->modalSubmitActionLabel;
        $this->options['modalCancelActionLabel'] = $this->modalCancelActionLabel;
        $this->options['modalDescription'] = $this->modalDescription;
        $this->options['modalIcon'] = $this->modalIcon;
        $this->options['modalSize'] = ($this->modalSize instanceof BackedEnum ? $this->modalSize->value : $this->modalSize);

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

    public function routeActionHandler(Request $request)
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

        $actions = [];
        // get actions
        if (method_exists($request->post('_action_handler'), 'getActions')) {
            $actions = call_user_func([$request->post('_action_handler'), 'getActions']);
        }

        // get table actions
        if (method_exists($request->post('_action_handler'), 'getTableActions')) {
            $actions = $actions->merge(call_user_func([$request->post('_action_handler'), 'getTableActions']));
        }
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

                $actions['modalTitle'] = str($actions['label'] . ' ' . $this->getModule()::getInstanceModel()->getTable())->headline()->singular()->toString();

                $this->callHook($actions['beforeFormFilled']);

                return response()->json([
                    'action_handler' => $request->post('_action_handler'),
                    'action_name' => $request->post('_action_name'),
                    'action_type' => 'action',
                    'id' => $request->post('_id'),
                    'html' => view('larascaff::form', [
                        'form' => $form,
                        'actions' => $actions,
                    ])->render(),
                ]);

                break;
            case 'action':
                if ($actions['isCustomAction']) {
                    $this->fillFormData();

                    foreach ($actions as $key => $action) {
                        $this->{$key} = $action;
                    }

                    $this->form = ! $actions['form'] ? Arr::get($this->getModule()::getActions(), 'create.form') : $actions['form'];

                    return $this->actionHandler($request, getRecord(), $actions['action']);
                }

                return $this->resolveClosureParams($actions['action']);

                break;
        }
    }

    protected function actionHandler(Request $request, Model $record, ?Closure $action = null): \Illuminate\Http\JsonResponse
    {
        if ($this->getPermission()) {
            Gate::authorize($this->getPermission() . ' ' . $this->getModule()::getUrl());
        }

        $this->inspectFormBuilder($this->getForm()->getComponents());

        if ($this->withValidations) {
            $request->validate($this->validations['validations'] ?? [], $this->validations['messages'] ?? []);
        }

        if (larascaffConfig()->isDatabaseTransactions()) {
            DB::beginTransaction();
        }

        try {
            $this->callEditFormData($this->editFormData);

            if ($this->name == 'edit') {
                $this->oldModelValue = $record->replicate();
            }

            if (! $this->isCustomAction) {
                if ($this->name == 'replicate') {
                    $this->replica = $record->replicate()->fill($this->formData);
                } else {
                    $record->fill($this->formData);
                }
            }

            $this->callHook($this->beforeSave);

            if (! $this->isCustomAction) {
                if ($this->name == 'replicate') {
                    $this->replica->save();
                } else {
                    $record->save();
                }
            }

            if ($action && is_callable($action)) {
                $this->resolveClosureParams($action);
            }

            setRecord($record);

            if (! $this->isCustomAction) {
                foreach ($this->getMedia() as $input) {
                    $this->uploadMediaHandler(input: $input, model: $record);
                }

                foreach ($this->getRelationship() as $input) {
                    $this->relationshipHandler(input: $input, model: $record);
                }
            }

            $this->callHook($this->afterSave);

            if (larascaffConfig()->isDatabaseTransactions()) {
                DB::commit();
            }

            $notification = $this->getNotification();

            return response()->json([
                'status' => $notification['type'],
                'title' => $notification['title'],
                'message' => $notification['body'],
                'position' => $notification['position'],
            ]);
        } catch (\Throwable $th) {
            if (larascaffConfig()->isDatabaseTransactions()) {
                DB::rollBack();
            }

            return responseError($th);
        }
    }

    protected function resolveClosureParams(?callable $cb = null)
    {
        if (! $cb instanceof Closure) {
            throw new \Exception('Param must be callable');
        }

        $parameters = [];
        foreach ((new \ReflectionFunction($cb))->getParameters() as $parameter) {
            $default = match ($parameter->getName()) {
                'record' => [$parameter->getName() => getRecord()],
                'model' => [$parameter->getName() => $this->getModule()::getModel()],
                'data' => [$parameter->getName() => $this->getFormData()],
                'form' => [$parameter->getName() => app()->make(Form::class)->module($this->getModule())],
                'info' => [$parameter->getName() => app()->make(Info::class)->module($this->getModule())],
                'replica' => [$parameter->getName() => $this->replica],
                default => []
            };

            $type = match ($parameter->getType()?->getName()) {
                $this->getModule()::getModel() => [$parameter->getName() => getRecord()],
                Form::class => [$parameter->getName() => Arr::get($default, 'form', app()->make(Form::class)->module($this->getModule()))],
                Info::class => [$parameter->getName() => Arr::get($default, 'info', app()->make(Info::class)->module($this->getModule()))],
                default => []
            };

            $parameters = [...$parameters, ...$type, ...$default];
        }

        return app()->call($cb, $parameters);
    }
}
