<?php

namespace Peterzaccha\JoQueryGenerator;

use Illuminate\Support\ServiceProvider;
use Peterzaccha\JoQueryGenerator\Controllers\Router;
use Peterzaccha\JoQueryGenerator\Services\JoQueryGenerator;

class JoQueryGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/dy-form.php' => config_path('dy-form.php'),
        ], 'config');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');
    }



    public function register()
    {
        $this->app->bind('joquerygenerator', function () {
            return new JoQueryGenerator();
        });
        $this->app->make('Peterzaccha\JoQueryGenerator\Controllers\Router');

    }
}
