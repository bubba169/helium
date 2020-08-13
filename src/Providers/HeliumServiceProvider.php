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
            __DIR__.'/../../resources/lang' => resource_path('lang/vendor/helium'),
            __DIR__.'/../../resources/views' => resource_path('views/vendor/helium'),
        ]);

        $this->loadRoutesFrom(__DIR__.'/../../config/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadFactoriesFrom(__DIR__.'/../../database/factories');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'helium');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'helium');
    }
}
