<?php

namespace Mulaidarinull\Larascaff\Console\Commands;

use Illuminate\Contracts\Console\PromptsForMissingInput;

use function Laravel\Prompts\text;

class MakeComponent extends BaseCommand implements PromptsForMissingInput
{
    protected $signature = 'make:larascaff-component
    {name : The name of component}';

    protected $description = 'Create a component';

    protected string $view;

    protected string $componentName;

    protected function promptForMissingArgumentsUsing()
    {
        return [
            'name' => fn () => text(
                label: 'Component name',
                placeholder: 'The name of component',
            ),
        ];
    }

    public function handle()
    {
        $name = $this->argument('name');
        if (strtolower(substr($name, -9)) == 'component') {
            $name = substr($name, 0, strlen($name) - 9);
        }

        $this->pathList = array_map(fn ($item) => ucfirst($item), explode('/', $name));
        $this->componentName = array_pop($this->pathList);
        $this->path = implode('/', $this->pathList);
        $this->view = strtolower(($this->path != '' ? $this->path.'/' : '')."{$this->componentName}");

        $this->makeComponent();
        $this->makeView();
    }

    public function makeView()
    {
        $stubFile = $this->resolveStubPath('/../../stubs/larascaff.component-view.stub');
        $file = $this->laravel->basePath('/resources/views/pages/'."{$this->view}-component.blade.php");
        $this->makeDirectory(dirname($file));
        $this->saveStub($stubFile, [], $file, 'View');
    }

    public function makeComponent()
    {
        $pageClass = $this->componentName.'Component';

        $replaces = [
            '{{ namespace }}' => 'App\\Larascaff\\Components'.(count($this->pathList) ? '\\' : '').implode('\\', $this->pathList),
            '{{ class }}' => $pageClass,
            '{{ view }}' => 'pages.'.str_replace('/', '.', $this->view).'-component',
        ];

        $stubFile = $this->resolveStubPath('/../../stubs/larascaff.component.stub');
        $file = $this->laravel->basePath('/app/Larascaff/Components'.($this->path != '' ? '/'.$this->path : '')."/{$pageClass}.php");
        $this->makeDirectory(dirname($file));
        $this->saveStub($stubFile, $replaces, $file, 'Component');
    }
}
