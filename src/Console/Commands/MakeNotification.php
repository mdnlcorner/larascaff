<?php

namespace Mulaidarinull\Larascaff\Console\Commands;

use Illuminate\Contracts\Console\PromptsForMissingInput;

use function Laravel\Prompts\text;

class MakeNotification extends BaseCommand implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:larascaff-notification 
    {name : The name of notification}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a notification';

    protected $notificationName;

    protected $prefix = 'Notification';

    protected function promptForMissingArgumentsUsing()
    {
        return [
            'name' => fn () => text(
                label: $this->prefix . ' name',
                placeholder: 'The name of ' . strtolower($this->prefix),
            ),
        ];
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        if (strtolower(substr($name, -12)) == strtolower($this->prefix)) {
            $name = substr($name, 0, strlen($name) - 4);
        }

        $this->pathList = array_map(fn ($item) => ucfirst($item), explode('/', $name));

        $this->notificationName = array_pop($this->pathList);

        $this->path = implode('/', $this->pathList);

        $this->makeNotification();
    }

    public function makeNotification()
    {
        $notificationClass = $this->notificationName . $this->prefix;

        $replaces = [
            '{{ namespace }}' => 'App\\Larascaff\\' . $this->prefix . 's' . (count($this->pathList) ? '\\' : '') . implode('\\', $this->pathList),
            '{{ class }}' => $notificationClass,
        ];

        $stubFile = $this->resolveStubPath('/../../stubs/larascaff.notification.stub');

        $file = $this->laravel->basePath("/app/Larascaff/{$this->prefix}s" . ($this->path != '' ? '/' . $this->path : '') . "/{$notificationClass}.php");

        $this->makeDirectory(dirname($file));
        $this->saveStub($stubFile, $replaces, $file, $this->prefix);
    }
}
