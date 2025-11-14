<?php

namespace Mulaidarinull\Larascaff;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Mulaidarinull\Larascaff\Assets\AssetManager;
use Mulaidarinull\Larascaff\Colors\ColorManager;
use Mulaidarinull\Larascaff\Models\Record;
use Mulaidarinull\Larascaff\View\Components\AppLayout;
use Mulaidarinull\Larascaff\View\Components\GuestLayout;

class LarascaffServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerCommands();
        $this->shouldPublishes();

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'larascaff');
        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'larascaff');

        Blade::component('larascaff-layout', AppLayout::class);
        Blade::component('larascaff-guest-layout', GuestLayout::class);
        Blade::directive('larascaffColors', function (): string {
            return "<?= \Mulaidarinull\Larascaff\Facades\LarascaffAsset::renderColorVariants() ?>";
        });
        Blade::directive('larascaffPlugins', function (): string {
            return "<?= \Mulaidarinull\Larascaff\Facades\LarascaffAsset::getRegisteredPlugins() ?>";
        });
    }

    public function register()
    {
        $this->app->singleton('larascaff', fn () => new LarascaffHandler);

        $this->app->singleton('larascaff.color', fn () => new ColorManager);

        $this->app->singleton('larascaff.asset', fn () => new AssetManager);

        $this->app->singleton(Record::class);

        $this->app->singleton(LarascaffConfig::class);

        $this->mergeConfigFrom(
            __DIR__.'/../config/larascaff.php',
            'larascaff'
        );
    }

    protected function shouldPublishes(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/lang' => $this->app->langPath('vendor/larascaff'),
            ], 'larascaff-translation');

            $this->publishes([
                __DIR__.'/../config/larascaff.php' => config_path('larascaff.php'),
                __DIR__.'/../config/permission.php' => config_path('permission.php'),
                __DIR__.'/../config/blade-tabler-icons.php' => config_path('blade-tabler-icons.php'),
            ], 'larascaff-config');

            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'larascaff-migration');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/larascaff'),
            ], 'larascaff-views');
        }
    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\MakeModule::class,
                Console\Commands\MakePage::class,
                Console\Commands\MakeComponent::class,
                Console\Commands\MakeWidget::class,
                Console\Commands\MakeNotification::class,
                Console\Commands\Install::class,
                Console\Commands\LinkAsset::class,
                Console\Commands\UnlinkAsset::class,
                Console\Commands\DeleteTempUploadFiles::class,
                Console\Commands\LarascaffSeederCommand::class,
            ]);
        }
    }
}
