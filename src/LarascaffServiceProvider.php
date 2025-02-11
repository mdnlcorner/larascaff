<?php

namespace Mulaidarinull\Larascaff;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Mulaidarinull\Larascaff\Models\Record;
use Mulaidarinull\Larascaff\View\Components\{AppLayout, Auth, GuestLayout};

class LarascaffServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->shouldPublishes();
        $this->registerCommands();
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'larascaff');
        Blade::component('larascaff-layout', AppLayout::class);
        Blade::component('larascaff-guest-layout', GuestLayout::class);
    }

    protected function shouldPublishes(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/larascaff.php' => config_path('larascaff.php'),
                __DIR__ . '/../config/permission.php' => config_path('permission.php'),
                __DIR__ . '/../config/blade-tabler-icons.php' => config_path('blade-tabler-icons.php'),
            ], 'larascaff-config');

            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'larascaff-migration');

            $this->publishes([
                __DIR__ . '/resources/views' => resource_path('views/vendor/larascaff'),
            ], 'larascaff-views');
        }
    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\MakeModule::class,
                Console\Commands\MakePage::class,
                Console\Commands\Install::class,
                Console\Commands\LinkAsset::class,
                Console\Commands\MakeComponent::class,
                Console\Commands\DeleteTempUploadFiles::class,
            ]);
        }
    }

    public function register()
    {
        $this->app->bind('Larascaff', function ($app) {
            return new Larascaff();
        });
        $this->app->singleton(Record::class, function() {
            return new Record();
        });
        $this->app->singleton(LarascaffConfig::class, function() {
            return new LarascaffConfig();
        });
        $this->mergeConfigFrom(
            __DIR__ . '/../config/larascaff.php',
            'larascaff'
        );
    }
}
