<?php

namespace I9code\LaravelMetronic472;

use I9code\LaravelMetronic472\Console\AdminMetronic472MakeCommand;
use I9code\LaravelMetronic472\Console\MakeMetronic472Command;
use I9code\LaravelMetronic472\Events\BuildingMenu;
use I9code\LaravelMetronic472\Http\ViewComposers\AdminMetronic472Composer;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->singleton(AdminMetronic472::class, function (Container $app) {
            return new AdminLte(
                $app['config']['adminMetronic472.filters'],
                $app['events'],
                $app
            );
        });
    }

    public function boot(
        Factory $view,
        Dispatcher $events,
        Repository $config
    ) {
        $this->loadViews();

        $this->loadTranslations();

        $this->publishConfig();

        $this->publishAssets();

        $this->registerCommands();

        $this->registerViewComposers($view);

        static::registerMenu($events, $config);
    }

    private function loadViews()
    {
        $viewsPath = $this->packagePath('resources/views');

        $this->loadViewsFrom($viewsPath, 'metronic472');

        $this->publishes([
            $viewsPath => base_path('resources/views/vendor/metronic472'),
        ], 'views');
    }

    private function loadTranslations()
    {
        $translationsPath = $this->packagePath('resources/lang');

        $this->loadTranslationsFrom($translationsPath, 'metronic472');

        $this->publishes([
            $translationsPath => base_path('resources/lang/vendor/metronic472'),
        ], 'translations');
    }

    private function publishConfig()
    {
        $configPath = $this->packagePath('config/adminMetronic472.php');

        $this->publishes([
            $configPath => config_path('adminMetronic472.php'),
        ], 'config');

        $this->mergeConfigFrom($configPath, 'metronic472');
    }

    private function publishAssets()
    {
        $this->publishes([
            $this->packagePath('resources/assets') => public_path('vendor/metronic472'),
        ], 'assets');
    }

    private function packagePath($path)
    {
        return __DIR__."/../$path";
    }

    private function registerCommands()
    {
        // Laravel >=5.2 only
        if (class_exists('Illuminate\\Auth\\Console\\MakeAuthCommand')) {
            $this->commands(MakeMetronic472Command::class);
        } elseif (class_exists('Illuminate\\Auth\\Console\\AuthMakeCommand')) {
            $this->commands(AdminMetronic472MakeCommand::class);
        }
    }

    private function registerViewComposers(Factory $view)
    {
        $view->composer('metronic472::page', AdminMetronic472Composer::class);
    }

    public static function registerMenu(Dispatcher $events, Repository $config)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) use ($config) {
            $menu = $config->get('metronic472.menu');
            call_user_func_array([$event->menu, 'add'], $menu);
        });
    }
}
