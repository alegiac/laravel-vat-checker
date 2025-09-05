<?php

namespace Alegiac\LaravelVatChecker;

use Illuminate\Support\ServiceProvider;

class LaravelVatCheckerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-vat-checker.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'vat-checker');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-vat-checker', function () {
            return new LaravelVatChecker;
        });
    }
}
