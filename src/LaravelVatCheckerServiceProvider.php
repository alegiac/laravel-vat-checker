<?php

namespace Alegiac\LaravelVatChecker;

use Alegiac\LaravelVatChecker\Contracts\VatResponseInterface;
use Alegiac\LaravelVatChecker\Contracts\VatValidatorFactoryInterface;
use Alegiac\LaravelVatChecker\Factories\VatValidatorFactory;
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

        // Register the factory
        $this->app->singleton(VatValidatorFactoryInterface::class, function () {
            return new VatValidatorFactory();
        });

        // Register the response class
        $this->app->singleton(VatResponseInterface::class, function () {
            return new LaravelVatCheckerResponse();
        });

        // Register the main class to use with the facade
        $this->app->singleton('laravel-vat-checker', function ($app) {
            return new LaravelVatChecker(
                $app->make(VatValidatorFactoryInterface::class),
                $app->make(VatResponseInterface::class)
            );
        });
    }
}
