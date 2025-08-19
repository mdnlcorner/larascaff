<?php

namespace Mulaidarinull\Larascaff\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\text;

class Install extends BaseCommand
{
    public function __construct(protected Filesystem $filesystem)
    {
        parent::__construct(new Filesystem);
        $this->filesystemystem = $filesystem;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larascaff:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install larascaff';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $prefix = text(label: 'Prefix name', placeholder: 'Leave it blank if you don\'t want to use prefix');

        // copy and set path bootstrap file
        $this->saveStub(
            $this->resolveStubPath('/../../stubs/bootstrap/app.php'),
            [
                '{{ dashboard }}' => $prefix ? $prefix . '/dashboard' : '/dashboard',
            ],
            $this->laravel->basePath('bootstrap/app.php')
        );
        // copy and set service providers
        $this->saveStub(
            $this->resolveStubPath('/../../stubs/Providers/LarascaffProvider.php'),
            [
                '{{ prefix }}' => $prefix,
            ],
            app_path('Providers/LarascaffProvider.php')
        );

        $this->components->info('Copying asset files..');

        $this->filesystem->copy(__DIR__ . '/../../stubs/bootstrap/providers.php', base_path('bootstrap/providers.php'));

        $this->filesystem->copyDirectory(__DIR__ . '/../../stubs/resources', base_path('resources'));
        $this->filesystem->copyDirectory(__DIR__ . '/../../stubs/Larascaff', app_path('Larascaff'));
        $this->filesystem->copyDirectory(__DIR__ . '/../../stubs/rootFile', base_path(''));

        if (! $this->filesystem->isDirectory(public_path('larascaff')) && ! is_link(public_path('larascaff'))) {
            $this->call('larascaff:link');
        }

        $this->call('vendor:publish', [
            '--tag' => 'larascaff-migration',
        ]);

        // $this->call('vendor:publish', [
        //     '--tag' => 'larascaff-config',
        // ]);

        $this->updateNodePackages(callback: function ($packages) {
            return [
                '@tailwindcss/forms' => '^0.5.2',
                '@tailwindcss/typography' => '^0.5.12',
                'autoprefixer' => '^10.4.2',
                'glob' => '^10.3.12',
                'laravel-vite-plugin' => '^1.2.0',
                'tailwind-merge' => '^2.5.2',
                'tailwindcss' => '^3.4.13',
                'vite' => '^6.2.4',
            ] + $packages;
        }, dev: true);

        $this->updateNodePackages(callback: function ($packages) {
            return $packages;
        }, dev: false);

        $this->components->info('Installing and building Node dependencies.');

        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm install', 'pnpm run build']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn install', 'yarn run build']);
        } elseif (file_exists(base_path('bun.lockb'))) {
            $this->runCommands(['bun install', 'bun run build']);
        } elseif (file_exists(base_path('deno.lock'))) {
            $this->runCommands(['deno install', 'deno task build']);
        } else {
            $this->runCommands(['npm install', 'npm run build']);
        }

        $this->line('');
        $this->components->info('Larascaff installed successfully.');
    }

    /**
     * Run the given commands.
     *
     * @param  array  $commands
     * @return void
     */
    protected function runCommands($commands)
    {
        $process = Process::fromShellCommandline(implode(' && ', $commands), null, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (\RuntimeException $e) {
                $this->output->writeln('  <bg=yellow;fg=black> WARN </> ' . $e->getMessage() . PHP_EOL);
            }
        }

        $process->run(function ($type, $line) {
            $this->output->write('    ' . $line);
        });
    }

    /**
     * Update the "package.json" file.
     *
     * @param  bool  $dev
     * @return void
     */
    protected static function updateNodePackages(callable $callback, $dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_FORCE_OBJECT) . PHP_EOL
        );
    }
}
