<?php

namespace CodencoDev\EloquentModelTester;

use Illuminate\Support\ServiceProvider;

class ModelTesterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     */
    public function register()
    {

        // Register the main class to use with the facade
        $this->app->bind('model-tester', function () {
            return new ModelTester;
        });
    }
}
