<?php

namespace Mulaidarinull\Larascaff\Console\Commands;

use Illuminate\Contracts\Console\PromptsForMissingInput;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class MakeWidget extends BaseCommand implements PromptsForMissingInput
{
    protected $signature = 'make:larascaff-widget
    {name : The name of widget}
    {type : Type of widget}
    ';

    protected $description = 'Create a widget';

    protected string $view;

    protected string $widgetName;

    protected function promptForMissingArgumentsUsing()
    {
        return [
            'name' => fn() => text(
                label: 'Widget name',
                placeholder: 'The name of Widget',
            ),
            'type' => fn() => select(
                label: 'Widget type',
                options: ['stat', 'chart', 'table']
            )
        ];
    }

    public function handle()
    {
        $name = $this->argument('name');
        if (strtolower(substr($name, -6)) == 'widget') {
            $name = substr($name, 0, strlen($name) - 6);
        }
        if (empty($name)) {
            $this->components->error('Widget not create');

            return;
        }

        $this->pathList = array_map(fn($item) => ucfirst($item), explode('/', $name));
        $this->widgetName = array_pop($this->pathList);
        $this->path = implode('/', $this->pathList);

        $this->makeWidget();
    }

    public function makeWidget()
    {
        $pageClass = $this->widgetName . 'Widget';

        $replaces = [
            '{{ namespace }}' => 'App\\Larascaff\\Widgets' . (count($this->pathList) ? '\\' : '') . implode('\\', $this->pathList),
            '{{ class }}' => $pageClass,
        ];

        $stubFile = match ($this->argument('type')) {
            'chart' => $this->resolveStubPath('/../../stubs/larascaff.widget-chart.stub'),
            'table' => $this->resolveStubPath('/../../stubs/larascaff.widget-table.stub'),
            default => $this->resolveStubPath('/../../stubs/larascaff.widget-stat.stub'),
        };

        $file = $this->laravel->basePath('/app/Larascaff/Widgets' . ($this->path != '' ? '/' . $this->path : '') . "/{$pageClass}.php");
        $this->makeDirectory(dirname($file));
        $this->saveStub($stubFile, $replaces, $file, 'Widget');
    }
}
