<?php

namespace Mulaidarinull\Larascaff;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Mulaidarinull\Larascaff\Colors\ColorManager;
use Mulaidarinull\Larascaff\Models\Record;
use Mulaidarinull\Larascaff\View\Components\AppLayout;
use Mulaidarinull\Larascaff\View\Components\GuestLayout;

class LarascaffServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->shouldPublishes();
        $this->registerCommands();
        $this->loadViewsFrom(__DIR__.'/resources/views', 'larascaff');
        Blade::component('larascaff-layout', AppLayout::class);
        Blade::component('larascaff-guest-layout', GuestLayout::class);
        Blade::directive('larascaffStyles', function (string $expression): string {
            return "<?php echo \Mulaidarinull\Larascaff\Facades\LarascaffAsset::renderStyles({$expression}) ?>";
        });
    }

    protected function shouldPublishes(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/larascaff.php' => config_path('larascaff.php'),
                __DIR__.'/../config/permission.php' => config_path('permission.php'),
                __DIR__.'/../config/blade-tabler-icons.php' => config_path('blade-tabler-icons.php'),
            ], 'larascaff-config');

            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'larascaff-migration');

            $this->publishes([
                __DIR__.'/resources/views' => resource_path('views/vendor/larascaff'),
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
                Console\Commands\UnlinkAsset::class,
                Console\Commands\MakeComponent::class,
                Console\Commands\DeleteTempUploadFiles::class,
            ]);
        }
    }

    public function register()
    {
        $this->app->scoped(
            LarascaffHandler::class,
            fn () => new LarascaffHandler
        );

        $this->app->scoped(
            ColorManager::class,
            fn () => new ColorManager
        );

        $this->app->singleton(
            Record::class,
            fn () => new Record
        );

        $this->app->singleton(
            LarascaffConfig::class,
            fn () => new LarascaffConfig
        );

        $this->mergeConfigFrom(
            __DIR__.'/../config/larascaff.php',
            'larascaff'
        );
    }
}
