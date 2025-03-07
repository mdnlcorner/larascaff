<?php

namespace Mulaidarinull\Larascaff;

use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Reflector;
use Illuminate\Support\Str;
use Mulaidarinull\Larascaff\Components\Forms\Form;
use Mulaidarinull\Larascaff\Components\Info\Info;
use Mulaidarinull\Larascaff\Datatable\BaseDatatable;
use Mulaidarinull\Larascaff\Traits\HasMenuPermission;
use Mulaidarinull\Larascaff\Traits\HasPermission;

abstract class BaseModule extends Controller
{
    use HasMenuPermission, HasPermission;

    /**
     * @var \Illuminate\Database\Eloquent\Model|string
     */
    protected $model;

    protected string $viewShow = '';

    protected string $viewAction = '';

    protected array $viewData = [];

    protected string $pageTitle = '';

    protected string $modalSize = 'md';

    protected string $modalTitle = '';

    protected string $url = '';

    protected array $validations = [];

    private ?string $routeKeyNameValue = null;

    private array $actions = [];

    private array $tableActions = [];

    public function __construct()
    {
        is_string($this->model) && $this->model = new $this->model;
        if ($this->modalTitle == '') {
            if ($this->model) {
                $this->modalTitle = 'Form '.ucwords(str_replace('_', ' ', $this->model->getTable()));
            }
        }

        $this->resolveUrl();
        if ($this->pageTitle == '') {
            $segments = explode('/', $this->url);
            if (count($segments)) {
                $this->pageTitle = ucwords(str_replace('-', ' ', array_pop($segments)));
            } else {
                $this->pageTitle = '';
            }
        }

        if (! count($this->actions)) {
            $this->actions['create'] = [
                'label' => 'Create',
                'action' => url($this->url.'/create'),
                'show' => fn () => true,
                'icon' => 'ti ti-copy-plus',
                'method' => 'get',
            ];
        }

        foreach ($this->actions as $action => $item) {
            if (user()?->cannot($action.' '.$this->url)) {
                unset($this->actions[$action]);
            }
        }

        $this->tableActions(permission: 'read', action: url($this->url.'/'.'{{id}}'), label: 'View', icon: 'tabler-eye');
        $this->tableActions(permission: 'update', action: url($this->url.'/'.'{{id}}'.'/edit'), label: 'Edit', icon: 'tabler-edit', color: 'warning');
        $this->tableActions(permission: 'delete', action: url($this->url.'/'.'{{id}}'), label: 'Delete', method: 'DELETE', icon: 'tabler-trash', color: 'danger');
    }

    public static function makeMenu()
    {
        $static = new static;
        $static->handleMakeMenu();
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function getTableActions()
    {
        return $this->tableActions;
    }

    public function actions($permission, $action, $label = null, $method = 'GET', Closure|null|bool $show = null, bool $ajax = true, bool $targetBlank = false, ?string $icon = null)
    {
        $this->permissions[$permission] = true;
        if (user()?->can($permission.' '.$this->url)) {
            if (is_bool($show)) {
                $show = fn () => $show;
            }
            $this->actions[$permission] = [
                'action' => $action,
                'label' => $label ?? ucfirst($permission),
                'method' => $method,
                'show' => $show ?? fn () => true,
                'ajax' => $ajax,
                'blank' => $targetBlank ? '_blank' : '',
                'icon' => $icon,
            ];
        }
    }

    public function tableActions(string $permission, string $action, ?string $label = null, string $method = 'GET', Closure|null|bool $show = null, bool $ajax = true, bool $targetBlank = false, ?string $icon = null, ?string $color = null)
    {
        $this->permissions[$permission] = true;
        if (user()?->can($permission.' '.$this->url)) {
            if (is_bool($show)) {
                $show = fn () => $show;
            }
            $this->tableActions[$permission] = [
                'action' => $action,
                'label' => $label ?? ucfirst($permission),
                'method' => $method,
                'show' => $show ?? fn () => true,
                'ajax' => $ajax,
                'blank' => $targetBlank ? '_blank' : '',
                'icon' => $icon ?? ($permission == 'update' ? 'tabler-edit' : ($permission == 'view' ? 'tabler-eye' : ($permission == 'delete' ? 'tabler-trash' : null))),
                'color' => $color ?? ($permission == 'update' ? 'warning' : ($permission == 'delete' ? 'danger' : null)),
            ];
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'pageTitle' => $this->pageTitle,
            'url' => Pluralizer::singular($this->url),
            'actions' => $this->actions,
            'tableActions' => $this->tableActions,
        ];

        if (method_exists($this, $method = 'widgets')) {
            $parameters = $this->resolveParameters($method, [$this->model]);
            $widgets = call_user_func_array([$this, $method], $parameters);

            $data['widgets'] = view('larascaff::widget', [
                'widgets' => $widgets,
            ]);
        }

        if (method_exists($this, 'table')) {
            $datatable = new BaseDatatable($this->model, $this->url, $this->tableActions);

            if (method_exists($this, 'filterTable')) {
                $filterTable = call_user_func([$this, 'filterTable']);
                $data['filterTable'] = view('larascaff::filter', [
                    'filterTable' => $filterTable,
                ]);
                $datatable->filterTable($filterTable);
            }
            call_user_func([$this, 'table'], $datatable);
            $render = $datatable->render('larascaff::main-content', $data);

            return $render;
        }

        return view('larascaff::main-content', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (! $request->ajax()) {
            return redirect()->to($this->url.'?action=create');
        }
        try {
            setRecord($this->model);
            $this->addDataToview([
                'action' => url($this->url),
            ]);

            // run hook before create
            if (method_exists($this, $method = 'shareData')) {
                $parameters = $this->resolveParameters($method, [$this->model, $request]);
                call_user_func_array([$this, $method], $parameters);
            }
            if (method_exists($this, $method = 'beforeCreate')) {
                $parameters = $this->resolveParameters($method, [$this->model, $request]);
                call_user_func_array([$this, $method], $parameters);
            }

            if (method_exists($this, $method = 'formBuilder')) {
                $view = view('larascaff::form-builder', ['form' => call_user_func_array([$this, $method], [new Form])]);
            } else {
                $view = view($this->viewAction, $this->viewData);
            }

            return $this->form($view, [
                'size' => $this->modalSize,
                'title' => $this->modalTitle,
                ...$this->viewData,
            ]);
        } catch (\Throwable $th) {
            return responseError($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->initValidation($request);
        $this->transformFormBuilder($request);
        $request->validate($this->validations['validations'] ?? [], $this->validations['messages'] ?? []);
        DB::beginTransaction();
        try {
            // run hook before store
            if (method_exists($this, $method = 'beforeStore')) {
                $parameters = $this->resolveParameters($method, [$this->model, $request]);
                call_user_func_array([$this, $method], $parameters);
            }

            $this->model->fill($request->all());
            $this->model->save();

            // handle form builder input
            $this->handleFormBuilder($request);

            // run hook after store
            if (method_exists($this, $method = 'afterStore')) {
                $parameters = $this->resolveParameters($method, [$this->model, $request]);
                call_user_func_array([$this, $method], $parameters);
            }

            DB::commit();

            return responseSuccess();
        } catch (\Throwable $th) {
            DB::rollBack();

            return responseError($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $this->routeKeyNameValue = $id;
        $this->getRecord();
        try {
            $this->addDataToview([
                'action' => null,
            ]);

            // run hook before show
            if (method_exists($this, $method = 'shareData')) {
                $parameters = $this->resolveParameters($method, [$this->model, $request]);
                call_user_func_array([$this, $method], $parameters);
            }
            if (method_exists($this, $method = 'beforeShow')) {
                $parameters = $this->resolveParameters($method, [$this->model, $request]);
                call_user_func_array([$this, $method], $parameters);
            }

            setRecord($this->model);

            if (method_exists($this, $method = 'infoList')) {
                $view = view('larascaff::form-builder', ['form' => call_user_func_array([$this, $method], [new Info])]);
            } elseif (method_exists($this, $method = 'formBuilder')) {
                $view = view('larascaff::form-builder', ['form' => call_user_func_array([$this, $method], [new Form])]);
            } else {
                $view = view($this->viewShow, $this->viewData);
            }

            return $this->form($view, [
                'size' => $this->modalSize,
                'title' => $this->modalTitle,
                ...$this->viewData,
            ]);
        } catch (\Throwable $th) {
            return responseError($th);
        }

        return response()->json([]);
    }

    public function getRecord()
    {
        if (! $this->routeKeyNameValue) {
            throw new \Exception('routeKeyNameValue must be filled');
        }

        return $this->model = $this->model->query()->where($this->model->getRouteKeyName(), $this->routeKeyNameValue)->firstOrFail();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        if (! $request->ajax()) {
            return redirect()->to($this->url.'?tableAction=update&tableActionId='.$id);
        }
        $this->routeKeyNameValue = $id;
        $this->getRecord();
        try {
            $this->addDataToview([
                'action' => url($this->url.'/'.$this->model->{$this->model->getRouteKeyName()}),
                'method' => 'PUT',
            ]);

            // run hook before edit
            if (method_exists($this, $method = 'shareData')) {
                $parameters = $this->resolveParameters($method, [$this->model, $request]);
                call_user_func_array([$this, $method], $parameters);
            }
            if (method_exists($this, $method = 'beforeEdit')) {
                $parameters = $this->resolveParameters($method, [$this->model, $request]);
                call_user_func_array([$this, $method], $parameters);
            }

            setRecord($this->model);

            if (method_exists($this, $method = 'formBuilder')) {
                $view = view('larascaff::form-builder', ['form' => call_user_func_array([$this, $method], [new Form])]);
            } else {
                $view = view($this->viewAction, $this->viewData);
            }

            return $this->form($view, [
                'size' => $this->modalSize,
                'title' => $this->modalTitle,
                ...$this->viewData,
            ]);
        } catch (\Throwable $th) {
            return responseError($th);
        }
    }

    public function form($view, array $formConfig = [])
    {
        return view('larascaff::form', ['slot' => $view, ...$formConfig]);
    }

    private function initValidation(Request $request)
    {
        if (method_exists($this, 'validationRules')) {
            $messages = [];
            if (method_exists($this, 'validationMessages')) {
                $messages = call_user_func([$this, 'validationMessages']);
            }
            foreach (call_user_func([$this, 'validationRules']) as $key => $validation) {
                $this->validations['validations'][$key] = $validation;
            }
            foreach ($messages as $key => $message) {
                $this->validations['messages'][$key] = $message;
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->routeKeyNameValue = $id;
        $this->getRecord();
        $this->initValidation($request);
        $this->transformFormBuilder($request);
        $request->validate($this->validations['validations'] ?? [], $this->validations['messages'] ?? []);
        DB::beginTransaction();
        try {
            // run hook before udpate
            if (method_exists($this, $method = 'beforeUpdate')) {
                $parameters = $this->resolveParameters($method, [$this->model, $request]);
                call_user_func_array([$this, $method], $parameters);
            }

            $this->model->fill($request->all());
            $this->model->save();

            // handle form builder
            $this->handleFormBuilder($request);

            // run hook after update
            if (method_exists($this, $method = 'afterUpdate')) {
                $parameters = $this->resolveParameters($method, [$this->model, $request]);
                call_user_func_array([$this, $method], $parameters);
            }

            DB::commit();

            return responseSuccess();
        } catch (\Throwable $th) {
            DB::rollBack();

            return responseError($th);
        }
    }

    protected function transformFormBuilder(Request $request)
    {
        if (method_exists($this, $method = 'formBuilder')) {
            setRecord($this->model);
            $parameters = $this->resolveParameters($method, []);

            $forms = call_user_func_array([$this, $method], $parameters);

            foreach ($forms->getComponents() as $form) {
                if (method_exists($form, 'getValidations')) {
                    foreach ($form->getValidations()['validations'] ?? [] as $key => $validation) {
                        $this->validations['validations'][$key] = $validation;
                    }
                    foreach ($form->getValidations()['messages'] ?? [] as $key => $validation) {
                        $this->validations['messages'][$key] = $validation;
                    }
                }

                if (method_exists($form, 'numberFormat')) {
                    if ($form->getNumberFormat()) {
                        $request->merge([$form->getName() => removeNumberFormat($request->{$form->getName()})]);
                    }
                }

                if (method_exists($form, 'getComponents')) {
                    $relationship = $form->getRelationship();

                    foreach ($form->getComponents() as $component) {
                        if (method_exists($component, 'numberFormat')) {
                            if ($component->getNumberFormat()) {
                                $request->merge([$component->getName() => removeNumberFormat($request->{$component->getName()})]);
                            }
                        }
                        if (method_exists($component, 'getValidations')) {
                            if ($relationship) {
                                if (count($component->getValidations()) && ! $this->model->{$relationship}() instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
                                    foreach ($component->getValidations()['validations'] ?? [] as $key => $validation) {
                                        $this->validations['validations'][$key] = $validation;
                                        // $this->validations['validations'][$relationship. '.'.$key.'.*'] = $validation;
                                    }
                                }
                            } else {
                                if (count($component->getValidations())) {
                                    foreach ($component->getValidations()['validations'] ?? [] as $key => $validation) {
                                        $this->validations['validations'][$key] = $validation;
                                    }
                                    foreach ($component->getValidations()['messages'] ?? [] as $key => $validation) {
                                        $this->validations['messages'][$key] = $validation;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function handleMedia(Request $request, $form, $model)
    {
        if ($form instanceof \Mulaidarinull\Larascaff\Components\Forms\Uploader) {
            if ((in_array('PUT', $request->route()->methods()) || in_array('PATCH', $request->route()->methods()))) {
                $model->updateMedia($form->getPath(), $request->{$form->getName()}, $form->getField());
            } elseif (in_array('POST', $request->route()->methods()) && $request->{$form->getName()}) {
                $model->storeMedia($form->getPath(), $request->{$form->getName()}, $form->getField());
            }
        }
    }

    protected function handleFormBuilder(Request $request)
    {
        if (method_exists($this, $method = 'formBuilder')) {
            setRecord($this->model);
            $parameters = $this->resolveParameters($method, []);

            $forms = call_user_func_array([$this, $method], $parameters);

            foreach ($forms->getComponents() as $form) {
                $this->handleMedia($request, $form, $this->model);
                // handle relationship input
                if ($form->getRelationship()) {
                    // form input that has sub components
                    if (method_exists($form, 'getComponents') && $form->getComponents()) {
                        $relationships = [];
                        $relationModel = $this->model->{$form->getRelationship()}();

                        foreach ($form->getComponents() as $component) {
                            $relationships[$form->getRelationship()][] = $component->getName();
                        }
                        $components[$form->getRelationship()] = $form;

                        foreach ($relationships as $relationName => $relationship) {
                            // update or create
                            if (
                                $relationModel instanceof \Illuminate\Database\Eloquent\Relations\MorphOne ||
                                $relationModel instanceof \Illuminate\Database\Eloquent\Relations\HasOne
                            ) {
                                foreach ($relationship as $item) {
                                    $relationInput[$item] = $request->input($item);
                                }
                                // if already exist, update
                                if ($this->model->{$relationName}) {
                                    $this->model->{$relationName}->fill($relationInput)->save();
                                } else {
                                    // store new record
                                    $this->model->{$relationName}()->create($relationInput);
                                }
                            } elseif ($relationModel instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
                                $inputs = [];
                                $related = ($this->model->{$relationName}()->getRelated());

                                for ($i = 0; $i < count($request->{$relationName}[$relationship[0]]); $i++) {
                                    $data = [];
                                    foreach ($request->{$relationName} as $name => $value) {
                                        $data[$name] = $value[$i];
                                    }
                                    $inputs[] = new $related($data);
                                }

                                $this->model->{$relationName}()->saveMany($inputs);
                            }
                        }
                    } else {
                        $relationship = $this->model->{$form->getRelationship()}();
                        if (
                            $relationship instanceof \Illuminate\Database\Eloquent\Relations\MorphToMany ||
                            $relationship instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany
                        ) {
                            $relationship->sync($request->{str_replace('[]', '', $form->getName())});
                        }
                    }
                } else {
                    // inside other component
                    if (method_exists($form, 'getComponents') && $form->getComponents()) {
                        foreach ($form->getComponents() as $component) {
                            $this->handleMedia($request, $component, $this->model);
                        }
                    }
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $this->routeKeyNameValue = $id;
        $this->getRecord();
        DB::beginTransaction();
        try {
            // run hook before delete
            if (method_exists($this, $method = 'beforeDelete')) {
                $parameters = $this->resolveParameters($method, [$this->model, $request]);
                call_user_func_array([$this, $method], $parameters);
            }

            $this->model->delete();

            // delete media
            if (method_exists($this, $method = 'formBuilder')) {
                setRecord($this->model);
                $parameters = $this->resolveParameters($method, []);

                $forms = call_user_func_array([$this, $method], $parameters);

                foreach ($forms->getComponents() as $form) {
                    if ($form instanceof \Mulaidarinull\Larascaff\Components\Forms\Uploader) {
                        $this->model->deleteMedia();
                    }
                }
            }

            // run hook after delete
            if (method_exists($this, $method = 'afterDelete')) {
                $parameters = $this->resolveParameters($method, [$this->model, $request]);
                call_user_func_array([$this, $method], $parameters);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return responseError($th);
        }

        return responseSuccess();
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

            if (! $found) {
                $parameters[] = $this->container->make($className);
            }
        }

        return $parameters;
    }

    private function resolveUrl()
    {
        $prefix = getPrefix();
        if ($prefix) {
            $prefix = $prefix .= '/';
        }

        if ($this->url == '') {
            if (! is_null($this->model?->url)) {
                $this->url = $this->model->url;
            } else {
                $url = explode('App\\Larascaff\\Modules\\', get_class($this));
                array_shift($url);
                $this->url = substr($url[0], 0, strlen($url[0]) - 6);
                $this->url = implode('/', array_map(function ($item) {
                    return Str::kebab($item);
                }, explode('\\', $this->url)));
                $this->url = Pluralizer::plural($this->url);
            }
        }
        $this->url = $prefix.$this->url;
    }

    protected function addDataToview(array $data)
    {
        $this->viewData = [...$this->viewData, ...$data];
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function makeRoute($url, string|Closure|array|null $action = null, $method = 'get', $name = null)
    {
        return compact('method', 'action', 'url', 'name');
    }
}
