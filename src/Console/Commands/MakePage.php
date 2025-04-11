<?php

namespace Mulaidarinull\Larascaff\Console\Commands;

use Illuminate\Contracts\Console\PromptsForMissingInput;
use Mulaidarinull\Larascaff\Traits\HasMenuPermission;

use function Laravel\Prompts\text;

class MakePage extends BaseCommand implements PromptsForMissingInput
{
    use HasMenuPermission;

    protected $signature = 'make:larascaff-page
    {name : The name of page}
    {--J|javascript : Add javascript file}
    ';

    protected $description = 'Create a page';

    protected $pageName;

    protected $modelClass;

    protected $view;

    protected function promptForMissingArgumentsUsing()
    {
        return [
            'name' => fn () => text(
                label: 'Page name',
                placeholder: 'The name of page',
            ),
        ];
    }

    public function handle()
    {
        $name = $this->argument('name');
        if (substr($name, -4) == 'page' || substr($name, -4) == 'Page') {
            $name = substr($name, 0, strlen($name) - 4);
        }

        $this->pathList = array_map(fn ($item) => ucfirst($item), explode('/', $name));

        $class = array_pop($this->pathList);

        $this->path = implode('/', $this->pathList);
        $this->pageName = $class;
        $this->view = strtolower(($this->path != '' ? $this->path . '/' : '') . "{$this->pageName}");

        $this->makeView();
        $this->makePage();
        if ($this->option('javascript')) {
            $this->makeJs();
        }
        $this->makeMenu($this->pageName, ['read']);
    }

    public function makeView()
    {
        $stubFile = $this->resolveStubPath('/../../stubs/larascaff.page-view.stub');
        $file = $this->laravel->basePath('/resources/views/pages/' . "{$this->view}.blade.php");
        $this->makeDirectory(dirname($file));
        $this->saveStub($stubFile, [], $file, 'View');
    }

    public function makeJs()
    {
        $this->js = strtolower(($this->path != '' ? $this->path . '/' : '') . "{$this->view}");

        $stubFile = $this->resolveStubPath('/../../stubs/larascaff.js.stub');
        $file = $this->laravel->basePath('/resources/js/pages/' . "{$this->js}.js");

        $this->makeDirectory(dirname($file));
        $this->saveStub($stubFile, [], $file, 'Javascript');
    }

    public function makePage()
    {
        $pageClass = $this->pageName . 'Page';

        $replaces = [
            '{{ namespace }}' => 'App\\Larascaff\\Pages' . (count($this->pathList) ? '\\' : '') . implode('\\', $this->pathList),
            '{{ class }}' => $pageClass,
            '{{ view }}' => 'pages.' . str_replace('/', '.', $this->view),
        ];

        $stubFile = $this->resolveStubPath('/../../stubs/larascaff.page.stub');
        $file = $this->laravel->basePath('/app/Larascaff/Pages' . ($this->path != '' ? '/' . $this->path : '') . "/{$pageClass}.php");
        $this->makeDirectory(dirname($file));
        $this->saveStub($stubFile, $replaces, $file, 'Page');
    }
}
