<?php

namespace Mulaidarinull\Larascaff\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use function Laravel\Prompts\{text};

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
    protected $description = 'Install Larascaff';

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
                '{{ dashboard }}' => $prefix ? $prefix . '/dashboard' : '/dashboard'
            ],
            $this->laravel->basePath("bootstrap/app.php")
        );
        // copy an d set service providers
        $this->saveStub(
            $this->resolveStubPath('/../../stubs/Providers/LarascaffServiceProvider.php'),
            [
                '{{ prefix }}' => $prefix
            ],
            app_path('Providers/LarascaffServiceProvider.php')
        );
        
        $this->components->info('Copying asset file');

        $this->filesystem->copy(__DIR__ . '/../../stubs/bootstrap/providers.php', base_path('bootstrap/providers.php'));
        $this->filesystem->copy(__DIR__ . '/../../stubs/UserFactory.php', database_path('factories/UserFactory.php'));
        $this->filesystem->copy(__DIR__ . '/../../stubs/User.php', app_path('Models/User.php'));
        $this->filesystem->copy(__DIR__ . '/../../stubs/Media.php', app_path('Models/Media.php'));

        $this->filesystem->copyDirectory(__DIR__ . '/../../stubs/Auth', app_path('Http/Controllers/Auth'));
        $this->filesystem->copyDirectory(__DIR__ . '/../../stubs/resources', base_path('resources'));
        $this->filesystem->copyDirectory(__DIR__ . '/../../stubs/Requests', app_path('Http/Requests'));
        $this->filesystem->copyDirectory(__DIR__ . '/../../stubs/Larascaff', app_path('Larascaff'));
        // $this->filesystem->copyDirectory(__DIR__ . '/../../stubs/Providers', app_path('Providers'));
        $this->filesystem->copyDirectory(__DIR__ . '/../../stubs/routes', base_path('routes'));
        $this->filesystem->copyDirectory(__DIR__ . '/../../stubs/seeders', database_path('seeders'));
        $this->filesystem->copyDirectory(__DIR__ . '/../../stubs/rootFile', base_path(''));

        if (!$this->filesystem->isDirectory(public_path('larascaff')) && !is_link(public_path('larascaff'))) {
            $this->call('larascaff:link-asset');
        }

        $this->call('vendor:publish', [
            '--tag' => 'larascaff-migration'
        ]);
        
        $this->call('vendor:publish', [
            '--tag' => 'larascaff-config'
        ]);


        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                "@tailwindcss/forms" => "^0.5.2",
                "@tailwindcss/typography" => "^0.5.12",
                "@types/dropzone" => "^5.7.8",
                "@types/nprogress" => "^0.2.3",
                "autoprefixer" => "^10.4.2",
                "axios" => "^1.6.4",
                "glob" => "^10.3.12",
                "laravel-vite-plugin" => "^1.0",
                "postcss" => "^8.4.31",
                "sass" => "^1.83.0",
                "tailwind-merge" => "^2.5.2",
                "tailwindcss" => "^3.3.1",
                "vite" => "^5.0",
            ] + $packages;
        });
        $this->updateNodePackages(function ($packages) {
            return [
                "@fullcalendar/core" => "^6.1.11",
                "@fullcalendar/daygrid" => "^6.1.11",
                "@fullcalendar/interaction" => "^6.1.11",
                "@fullcalendar/list" => "^6.1.11",
                "@fullcalendar/timegrid" => "^6.1.11",
                "@popperjs/core" => "^2.11.8",
                "dropzone" => "^6.0.0-beta.2",
                "flatpickr" => "^4.6.13",
                "izitoast" => "^1.4.0",
                "jquery" => "3.7.1",
                "simplebar" => "^6.2.5",
                "sweetalert2" => "^11.12.4",
                "swiper" => "^11.1.9",
                "nprogress" => "^0.2.0",
            ] + $packages;
        }, false);

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
     * @param  callable  $callback
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
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }
}
