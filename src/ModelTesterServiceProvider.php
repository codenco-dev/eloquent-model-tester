<?php

namespace CodencoDev\ExternalApiCallRecorder;

use CodencoDev\EloquentModelTester\ModelTester;
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
        $this->app->bind('external-api-call-recorder', function () {
            return new ModelTester;
        });
    }
}
