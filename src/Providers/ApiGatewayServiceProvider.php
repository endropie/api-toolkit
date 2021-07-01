<?php

namespace Endropie\ApiTool\Providers;

use Illuminate\Support\ServiceProvider;

class ApiGatewayServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('http', function ($app) {
            return new \Illuminate\Http\Client\Factory;
        });

        $this->app->singleton('microservice', function ($app) {
            return new \Endropie\ApiTool\Support\MicroService($app);
        });
    }

    public function boot()
    {
        $this->app['microservice']->router();
    }
}
