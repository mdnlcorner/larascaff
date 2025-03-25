<?php

namespace Mulaidarinull\Larascaff\Console\Commands;

use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;
use Mulaidarinull\Larascaff\Traits\HasMenuPermission;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\text;

class MakeModule extends BaseCommand implements PromptsForMissingInput
{
    use HasMenuPermission;

    protected $signature = 'make:larascaff-module
    {name : The name of module}
    {table? : The name of table}
    {--M|model= : The name of Model, if not present will create using name of module}
    {--N|notification : Make module has notification}
    {--J|javascript : Add javascript file}
    {--migration : Add migration}
    {--S|simple : Make module as simple module}
    ';

    protected $description = 'Create a module';

    protected $moduleName;

    protected $modelClass;

    protected $tableName;

    protected $namespaceModel;

    protected $pathModel;

    protected $view;

    protected $js;

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array
     */
    protected function promptForMissingArgumentsUsing()
    {
        return [
            'name' => fn () => text(
                label: 'Module name',
                placeholder: 'The name of module',
            ),
        ];
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        if (substr($name, -6) == 'module' || substr($name, -6) == 'Module') {
            $name = substr($name, 0, strlen($name) - 6);
        }

        $this->pathList = array_map(fn ($item) => ucfirst($item), explode('/', $name));
        if ($this->option('model')) {
            $model = explode('/', $this->option('model'));
        }

        $class = array_pop($this->pathList);
        $this->modelClass = ucfirst(isset($model) ? array_pop($model) : $class);
        $this->tableName = $this->argument('table') ?? Str::snake(Pluralizer::plural($this->modelClass));
        $this->namespaceModel = 'App\\Models'.(count($model ?? $this->pathList) ? '\\' : '').implode('\\', $model ?? $this->pathList);
        $this->path = implode('/', $this->pathList);
        $this->pathModel = implode('/', $model ?? $this->pathList);
        $this->moduleName = $class;
        $this->view = strtolower(($this->path != '' ? $this->path.'/' : '')."{$this->moduleName}");

        $this->makeModel();
        if ($this->option('javascript')) {
            $this->makeJs();
        }
        $this->makeMenu(Pluralizer::plural($this->moduleName, null));
        if ($this->option('simple')) {
            $this->makeView();
        }
        $this->makeModule();
    }

    public function makeView()
    {

        $stubFile = $this->resolveStubPath('/../../stubs/larascaff.form.stub');
        $file = $this->laravel->basePath('/resources/views/pages/'."{$this->view}-form.blade.php");
        $this->makeDirectory(dirname($file));
        $this->saveStub($stubFile, [], $file, 'View');
    }

    public function makeJs()
    {
        $this->js = strtolower(($this->path != '' ? $this->path.'/' : '')."{$this->moduleName}");

        $stubFile = $this->resolveStubPath('/../../stubs/larascaff.js.stub');
        $file = $this->laravel->basePath('/resources/js/pages/'."{$this->js}.js");
        $this->makeDirectory(dirname($file));
        $this->saveStub($stubFile, [], $file, 'Javascript');
    }

    public function makeModule()
    {
        $moduleClass = $this->moduleName.'Module';
        $replaces = [
            '{{ namespace }}' => 'App\\Larascaff\\Modules'.(count($this->pathList) ? '\\' : '').implode('\\', $this->pathList),
            '{{ modelNamespace }}' => $this->namespaceModel.'\\'.$this->modelClass,
            '{{ class }}' => $moduleClass,
            '{{ view }}' => str_replace('/', '.', $this->view),
            '{{ model }}' => $this->modelClass,
            '{{ modelVariable }}' => Pluralizer::singular($this->tableName),
        ];

        $stubFile = $this->resolveStubPath('/../../stubs/'.(! $this->option('simple') ? 'larascaff.module-builder.stub' : 'larascaff.module.stub'));
        $file = $this->laravel->basePath('/app/Larascaff/Modules'.($this->path != '' ? '/'.$this->path : '')."/{$moduleClass}.php");
        $this->makeDirectory(dirname($file));
        $this->saveStub($stubFile, $replaces, $file, 'Module');
    }

    protected function makeModel()
    {
        $replaces = [
            '{{ namespace }}' => $this->namespaceModel,
            '{{ class }}' => $this->modelClass,
            '{{ table }}' => $this->tableName,
            '{{ useNotificationTrait }}' => $this->option('notification') ? "\n"."use Mulaidarinull\Larascaff\Traits\HasNotification;" : '',
            '{{ notificationTrait }}' => $this->option('notification') ? ', HasNotification' : '',
        ];

        $stubFile = $this->resolveStubPath('/../../stubs/larascaff.model.stub');
        $file = $this->laravel->basePath('app/Models'.($this->pathModel != '' ? '/'.$this->pathModel : '')."/{$this->modelClass}.php");
        $this->makeDirectory(dirname($file));
        if ($this->fileSystem->exists($file)) {
            if (! confirm('Model already exist, using existing model or abort')) {
                $this->warn('Create Module abort!!. Module not create.');
                exit;
            }
            $this->components->info(sprintf('%s [%s] using existing model.', 'Model', $file));

            return;
        }
        $this->saveStub($stubFile, $replaces, $file, 'Model');
        if ($this->option('migration')) {
            $this->call('make:migration', ['name' => 'create'.Pluralizer::plural($this->moduleName, null).'_table']);
        }
    }
}
