<?php

namespace Mulaidarinull\Larascaff\Console\Commands;

use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\Schema;
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

    protected $modelNamespace;

    protected $baseModelClass;

    protected $modelClass;

    protected $tableName;

    protected $tableColumns = [];

    protected $pathModel;

    protected $view;

    protected $js;

    protected function promptForMissingArgumentsUsing(): array
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
        if (strtolower(substr($name, -6)) == 'module') {
            $name = substr($name, 0, strlen($name) - 6);
        }

        $this->pathList = array_map(fn ($item) => ucfirst($item), explode('/', $name));
        if ($this->option('model')) {
            $model = explode('/', $this->option('model'));
        }

        $this->moduleName = array_pop($this->pathList);

        $this->baseModelClass = ucfirst(isset($model) ? array_pop($model) : $this->moduleName);

        $this->tableName = $this->argument('table') ?? Str::snake(Pluralizer::plural($this->baseModelClass));

        $this->modelNamespace = 'App\\Models' . (count($model ?? $this->pathList) ? '\\' : '') . implode('\\', $model ?? $this->pathList);

        $this->modelClass = $this->modelNamespace . str($this->baseModelClass)->start('\\');

        $this->path = implode('/', $this->pathList);

        $this->pathModel = implode('/', $model ?? $this->pathList);

        $this->view = strtolower(($this->path != '' ? $this->path . '/' : '') . "{$this->moduleName}");

        if (Schema::hasTable($tableName = (new $this->modelClass)->getTable())) {
            $this->tableColumns = Schema::getColumns($tableName);
        }

        $this->makeModel();
        if ($this->option('javascript')) {
            $this->makeJs();
        }
        $this->makeMenu(Pluralizer::plural($this->moduleName, null));
        $this->makeModule();
    }

    protected function makeView()
    {
        $stubFile = $this->resolveStubPath('/../../stubs/larascaff.form.stub');

        $file = $this->laravel->basePath('/resources/views/pages/' . "{$this->view}-form.blade.php");

        $this->makeDirectory(dirname($file));
        $this->saveStub($stubFile, [], $file, 'View');
    }

    protected function makeJs()
    {
        $this->js = strtolower(($this->path != '' ? $this->path . '/' : '') . "{$this->moduleName}");

        $stubFile = $this->resolveStubPath('/../../stubs/larascaff.js.stub');

        $file = $this->laravel->basePath('/resources/js/pages/' . "{$this->js}.js");

        $this->makeDirectory(dirname($file));
        $this->saveStub($stubFile, [], $file, 'Javascript');
    }

    protected function makeModule()
    {
        $moduleClass = $this->moduleName . 'Module';

        $replaces = [
            '{{ namespace }}' => 'App\\Larascaff\\Modules' . (count($this->pathList) ? '\\' : '') . implode('\\', $this->pathList),
            '{{ modelNamespace }}' => $this->modelNamespace . '\\' . $this->baseModelClass,
            '{{ class }}' => $moduleClass,
            '{{ view }}' => str_replace('/', '.', $this->view),
            '{{ model }}' => $this->baseModelClass,
            '{{ modelVariable }}' => Pluralizer::singular($this->tableName),
            '{{ tableColumns }}' => $this->generateTableColumns(),
            '{{ forms }}' => $this->generateForms(),
        ];

        $stubFile = $this->resolveStubPath('/../../stubs/' . 'larascaff.module-builder.stub');

        $file = $this->laravel->basePath('/app/Larascaff/Modules' . ($this->path != '' ? '/' . $this->path : '') . "/{$moduleClass}.php");

        $this->makeDirectory(dirname($file));

        $this->saveStub($stubFile, $replaces, $file, 'Module');
    }

    protected function makeModel()
    {
        $replaces = [
            '{{ namespace }}' => $this->modelNamespace,
            '{{ class }}' => $this->baseModelClass,
            '{{ table }}' => $this->tableName,
            '{{ useNotificationTrait }}' => $this->option('notification') ? "\n" . "use Mulaidarinull\Larascaff\Traits\HasNotification;" : '',
            '{{ notificationTrait }}' => $this->option('notification') ? ', HasNotification' : '',
        ];

        $stubFile = $this->resolveStubPath('/../../stubs/larascaff.model.stub');

        $file = $this->laravel->basePath('app/Models' . ($this->pathModel != '' ? '/' . $this->pathModel : '') . "/{$this->baseModelClass}.php");

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
            $this->call('make:migration', ['name' => 'create' . Pluralizer::plural($this->moduleName, null) . '_table']);
        }
    }

    protected function generateForms()
    {
        $forms = [];
        foreach ($this->tableColumns as $column) {
            $name = $column['name'];
            $type = $column['type_name'];
            if (! in_array($name, ['id', 'uuid', 'created_at', 'updated_at'])) {
                if (in_array($type, ['date', 'datetime', 'timestamp'])) {
                    $forms[] = "Forms\Components\DatePicker::make('" . $name . "')";
                } else {
                    $forms[] = "Forms\Components\TextInput::make('" . $name . "')";
                }
            }
        }
        if (count($forms)) {
            return implode(',' . PHP_EOL . '            ', $forms) . ',';
        } else {
            return '//';
        }
    }

    protected function generateTableColumns(): string
    {
        $columns = [];
        foreach ($this->tableColumns as $column) {
            $name = $column['name'];
            $type = $column['type_name'];
            if (! in_array($name, ['id', 'uuid'])) {
                if (in_array($type, ['date', 'datetime', 'timestamp'])) {
                    $columns[] = "Tables\Columns\DateColumn::make('" . $name . "')";
                } else {
                    $columns[] = "Tables\Columns\TextColumn::make('" . $name . "')";
                }
            }
        }
        if (count($columns)) {
            return implode(',' . PHP_EOL . '                ', $columns) . ',';
        } else {
            return '//';
        }
    }
}
