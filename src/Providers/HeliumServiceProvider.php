<?php

namespace Helium\Providers;

use Illuminate\Support\ServiceProvider;

class HeliumServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/helium.php',
            'helium'
        );
        $this->mergeConfigFrom(
            __DIR__.'/../../config/helium/tiny.php',
            'helium.tiny'
        );
        $this->mergeConfigFrom(
            __DIR__.'/../../config/helium/purifier.php',
            'helium.purifier'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/helium.php' => config_path('helium.php'),
            __DIR__.'/../../config/helium/tiny.php' => config_path('helium/tiny.php'),
            __DIR__.'/../../config/helium/purifier.php' => config_path('helium/purifier.php'),
            __DIR__.'/../../resources/lang' => resource_path('lang/vendor/helium'),
        ], 'helium-config');

        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/helium'),
        ], 'helium-templates');

        $this->publishes([
            __DIR__.'/../../public' => public_path('vendor/helium'),
        ], 'helium-public');

        $this->loadRoutesFrom(__DIR__.'/../../config/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'helium');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'helium');
    }
}
