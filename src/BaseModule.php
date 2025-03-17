<?php

namespace Mulaidarinull\Larascaff;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Pluralizer;
use Mulaidarinull\Larascaff\Components\Forms\Form;
use Mulaidarinull\Larascaff\Components\Info\Info;
use Mulaidarinull\Larascaff\Concerns\ModuleAction;
use Mulaidarinull\Larascaff\Datatable\BaseDatatable;
use Mulaidarinull\Larascaff\Enums\ModalSize;
use Mulaidarinull\Larascaff\Traits\HasMenuPermission;
use Mulaidarinull\Larascaff\Traits\HasPermission;
use Mulaidarinull\Larascaff\Traits\ParameterResolver;

abstract class BaseModule extends Controller
{
    use HasMenuPermission, HasPermission, ParameterResolver;

    protected Model $oldModelValue;

    protected static ?string $model = null;

    protected static ?Model $instanceModel = null;

    protected static ?string $url = null;

    protected static ?string $pageTitle = null;

    protected static ?ModalSize $modalSize = ModalSize::Md;

    protected static ?string $modalTitle = null;

    protected static ?string $viewShow = null;

    protected static ?string $viewAction = null;

    protected static array $viewData = [];

    protected array $validations = [];

    private ?string $routeKeyNameValue = null;

    public static function getModel(): string
    {
        return static::$model ?? (string) str(static::class)
            ->beforeLast('Module')
            ->replace('App\\Larascaff\\Modules', '')
            ->prepend('App\\Models');
    }

    protected static function getInstanceModel(): Model
    {
        if (! static::$instanceModel) {
            $model = static::getModel();
            static::$instanceModel = new $model;
        }

        return static::$instanceModel;
    }

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

    public static function getActions(bool $validatePermission = false)
    {
        $url = static::getUrl();
        // default table actions => read, update & delete
        $actions = collect([
            ModuleAction::make(permission: 'create', url: '/create', label: 'Create', icon: 'tabler-plus'),
        ])
            ->flatMap(fn ($item) => $item)
            ->map(function ($item) use ($url) {
                $item['url'] = url($url.$item['url']);

                return $item;
            })->toArray();

        // add custom table actions
        if (method_exists(static::class, $method = 'actions')) {
            $actions = [...$actions, ...collect(call_user_func([static::class, $method]))
                ->flatMap(fn ($item) => $item)
                ->map(function ($item) use ($url) {
                    $item['url'] = url($url.$item['url']);

                    return $item;
                })->toArray()];
        }

        if ($validatePermission) {
            return array_filter($actions, function ($permission) use ($url) {
                return user()->can($permission.' '.$url);
            }, ARRAY_FILTER_USE_KEY);
        }

        return $actions;
    }

    public static function getTableActions(bool $validatePermission = false)
    {
        $url = static::getUrl();
        // default table actions => read, update & delete
        $tableActions = collect([
            ModuleAction::make(permission: 'read', url: '/{{id}}', label: 'View', icon: 'tabler-eye'),
            ModuleAction::make(permission: 'update', url: '/{{id}}/edit', label: 'Edit', icon: 'tabler-edit', color: 'warning'),
            ModuleAction::make(permission: 'delete', url: '/{{id}}', label: 'Delete', method: 'DELETE', icon: 'tabler-trash', color: 'danger'),
        ])
            ->flatMap(fn ($item) => $item)
            ->map(function ($item) use ($url) {
                $item['url'] = url($url.$item['url']);

                return $item;
            })->toArray();

        // add custom table actions
        if (method_exists(static::class, $method = 'tableActions')) {
            $tableActions = [...$tableActions, ...collect(call_user_func([static::class, $method]))
                ->flatMap(fn ($item) => $item)
                ->map(function ($item) use ($url) {
                    $item['url'] = url($url.$item['url']);

                    return $item;
                })->toArray()];
        }

        if ($validatePermission) {
            return array_filter($tableActions, function ($permission) use ($url) {
                return user()->can($permission.' '.$url);
            }, ARRAY_FILTER_USE_KEY);
        }

        return $tableActions;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'pageTitle' => static::getPageTitle(),
            'url' => Pluralizer::singular(static::getUrl()),
            'actions' => static::getActions(true),
            'tableActions' => static::getTableActions(true),
        ];

        if (method_exists($this, $method = 'widgets')) {
            $parameters = $this->resolveParameters($method, [static::getInstanceModel()]);
            $widgets = call_user_func_array([$this, $method], $parameters);

            $data['widgets'] = view('larascaff::widget', [
                'widgets' => $widgets,
            ]);
        }

        if (method_exists($this, 'table')) {
            $datatable = new BaseDatatable(static::getInstanceModel(), static::getUrl(), static::getTableActions());

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

    public static function getModalTitle()
    {
        $title = static::$modalTitle;
        if (! $title) {
            if (static::getInstanceModel()) {
                $title = 'Form '.ucwords(str_replace('_', ' ', static::getInstanceModel()->getTable()));
            }
        }

        return $title;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (! $request->ajax()) {
            return redirect()->to(static::getUrl().'?action=create');
        }
        try {
            setRecord(static::getInstanceModel());
            $this->addDataToview([
                'action' => url(static::getUrl()),
            ]);

            // run hook before create
            if (method_exists($this, $method = 'shareData')) {
                $parameters = $this->resolveParameters($method, [static::getInstanceModel(), $request]);
                call_user_func_array([$this, $method], $parameters);
            }
            if (method_exists($this, $method = 'beforeCreate')) {
                $parameters = $this->resolveParameters($method, [static::getInstanceModel(), $request]);
                call_user_func_array([$this, $method], $parameters);
            }

            if (method_exists($this, $method = 'formBuilder')) {
                $view = view('larascaff::form-builder', ['form' => call_user_func_array([$this, $method], [new Form])]);
            } else {
                $view = view(static::$viewAction, static::$viewData);
            }

            return $this->form($view, [
                'size' => static::$modalSize,
                'title' => static::getModalTitle(),
                ...static::$viewData,
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
                $parameters = $this->resolveParameters($method, [static::getInstanceModel(), $request]);
                call_user_func_array([$this, $method], $parameters);
            }

            static::getInstanceModel()->fill($request->all());
            static::getInstanceModel()->save();

            // handle form builder input
            $this->handleFormBuilder($request);

            // run hook after store
            if (method_exists($this, $method = 'afterStore')) {
                $parameters = $this->resolveParameters($method, [static::getInstanceModel(), $request]);
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
                $parameters = $this->resolveParameters($method, [static::getInstanceModel(), $request]);
                call_user_func_array([$this, $method], $parameters);
            }
            if (method_exists($this, $method = 'beforeShow')) {
                $parameters = $this->resolveParameters($method, [static::getInstanceModel(), $request]);
                call_user_func_array([$this, $method], $parameters);
            }

            setRecord(static::getInstanceModel());

            if (method_exists($this, $method = 'infoList')) {
                $view = view('larascaff::form-builder', ['form' => call_user_func_array([$this, $method], [new Info])]);
            } elseif (method_exists($this, $method = 'formBuilder')) {
                $view = view('larascaff::form-builder', ['form' => call_user_func_array([$this, $method], [new Form])]);
            } else {
                $view = view(static::$viewShow, static::$viewData);
            }

            return $this->form($view, [
                'size' => static::$modalSize,
                'title' => static::getModalTitle(),
                ...static::$viewData,
            ]);
        } catch (\Throwable $th) {
            return responseError($th);
        }

        return response()->json([]);
    }

    public function getRecord(): Model
    {
        if (! $this->routeKeyNameValue) {
            throw new \Exception('routeKeyNameValue must be filled');
        }
        static::$instanceModel = static::getInstanceModel()->query()->where(static::getInstanceModel()->getRouteKeyName(), $this->routeKeyNameValue)->firstOrFail();

        return static::$instanceModel;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        if (! $request->ajax()) {
            return redirect()->to(static::getUrl().'?tableAction=update&tableActionId='.$id);
        }
        $this->routeKeyNameValue = $id;
        $this->getRecord();
        try {
            $this->addDataToview([
                'action' => url(static::getUrl().'/'.static::getInstanceModel()->{static::getInstanceModel()->getRouteKeyName()}),
                'method' => 'PUT',
            ]);

            // run hook before edit
            if (method_exists($this, $method = 'shareData')) {
                $parameters = $this->resolveParameters($method, [static::getInstanceModel(), $request]);
                call_user_func_array([$this, $method], $parameters);
            }
            if (method_exists($this, $method = 'beforeEdit')) {
                $parameters = $this->resolveParameters($method, [static::getInstanceModel(), $request]);
                call_user_func_array([$this, $method], $parameters);
            }

            setRecord(static::getInstanceModel());

            if (method_exists($this, $method = 'formBuilder')) {
                $view = view('larascaff::form-builder', ['form' => call_user_func_array([$this, $method], [new Form])]);
            } else {
                $view = view(static::$viewAction, static::$viewData);
            }

            return $this->form($view, [
                'size' => static::$modalSize,
                'title' => static::getModalTitle(),
                ...static::$viewData,
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
                $parameters = $this->resolveParameters($method, [static::getInstanceModel(), $request]);
                call_user_func_array([$this, $method], $parameters);
            }

            $this->oldModelValue = static::getInstanceModel()->replicate();
            static::getInstanceModel()->fill($request->all());
            static::getInstanceModel()->save();

            // handle form builder
            $this->handleFormBuilder($request);

            // run hook after update
            if (method_exists($this, $method = 'afterUpdate')) {
                $parameters = $this->resolveParameters($method, [static::getInstanceModel(), $request]);
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
            setRecord(static::getInstanceModel());
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
                                if (count($component->getValidations()) && ! static::getInstanceModel()->{$relationship}() instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
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
                $model->oldModelValue = $this->oldModelValue;
                $model->updateMedia($form->getPath(), $request->{$form->getName()}, $form->getField());
            } elseif (in_array('POST', $request->route()->methods()) && $request->{$form->getName()}) {
                $model->storeMedia($form->getPath(), $request->{$form->getName()}, $form->getField());
            }
        }
    }

    protected function handleFormBuilder(Request $request)
    {
        if (method_exists($this, $method = 'formBuilder')) {
            setRecord(static::getInstanceModel());
            $parameters = $this->resolveParameters($method, []);

            $forms = call_user_func_array([$this, $method], $parameters);

            foreach ($forms->getComponents() as $form) {
                $this->handleMedia($request, $form, static::getInstanceModel());
                // handle relationship input
                if ($form->getRelationship()) {
                    // form input that has sub components
                    if (method_exists($form, 'getComponents') && $form->getComponents()) {
                        $relationships = [];
                        $relationModel = static::getInstanceModel()->{$form->getRelationship()}();

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
                                if (static::getInstanceModel()->{$relationName}) {
                                    static::getInstanceModel()->{$relationName}->fill($relationInput)->save();
                                } else {
                                    // store new record
                                    static::getInstanceModel()->{$relationName}()->create($relationInput);
                                }
                            } elseif ($relationModel instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
                                $inputs = [];
                                $related = (static::getInstanceModel()->{$relationName}()->getRelated());

                                for ($i = 0; $i < count($request->{$relationName}[$relationship[0]]); $i++) {
                                    $data = [];
                                    foreach ($request->{$relationName} as $name => $value) {
                                        $data[$name] = $value[$i];
                                    }
                                    $inputs[] = new $related($data);
                                }

                                static::getInstanceModel()->{$relationName}()->saveMany($inputs);
                            }
                        }
                    } else {
                        $relationship = static::getInstanceModel()->{$form->getRelationship()}();
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
                            $this->handleMedia($request, $component, static::getInstanceModel());
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
                $parameters = $this->resolveParameters($method, [static::getInstanceModel(), $request]);
                call_user_func_array([$this, $method], $parameters);
            }

            static::getInstanceModel()->delete();

            // delete media
            if (method_exists($this, $method = 'formBuilder')) {
                setRecord(static::getInstanceModel());
                $parameters = $this->resolveParameters($method, []);

                $forms = call_user_func_array([$this, $method], $parameters);

                foreach ($forms->getComponents() as $form) {
                    if ($form instanceof \Mulaidarinull\Larascaff\Components\Forms\Uploader) {
                        static::getInstanceModel()->deleteMedia();
                    }
                }
            }

            // run hook after delete
            if (method_exists($this, $method = 'afterDelete')) {
                $parameters = $this->resolveParameters($method, [static::getInstanceModel(), $request]);
                call_user_func_array([$this, $method], $parameters);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return responseError($th);
        }

        return responseSuccess();
    }

    protected function addDataToview(array $data)
    {
        static::$viewData = [...static::$viewData, ...$data];
    }

    public static function getUrl(): string
    {
        $url = static::$url;
        if (! $url) {
            $url = substr(static::class, strlen('App\\Larascaff\\Modules\\'));
            $url = substr($url, 0, strlen($url) - 6);
            $url = implode('/', array_map(function ($item) {
                return \Illuminate\Support\Str::kebab($item);
            }, explode('\\', $url)));
            $url = Pluralizer::plural($url);
        }

        return (getPrefix() ? getPrefix().'/' : '').$url;
    }

    public static function makeRoute($url, string|\Closure|array|null $action = null, $method = 'get', $name = null)
    {
        return compact('method', 'action', 'url', 'name');
    }
}
