<?php

namespace Endropie\ApiToolkit\Providers;

use Illuminate\Support\ServiceProvider;
use Endropie\ApiToolkit\Auth\JWTMicro;

class JWTMicroAuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app['auth']->provider(
            'jwt-micro-provider',
            function ($app, array $config) {
                return new JWTMicro();
            }
        );
        Config::set('auth.guards.api.driver', 'jwt');
        Config::set('auth.guards.api.provider', 'micro');
        Config::set('auth.providers.micro.driver', 'jwt-micro-provider');
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // app('config')->set('auth.guards.api.driver', 'jwt');
        // app('config')->set('auth.guards.api.provider', 'micro');
        // app('config')->set('auth.providers.micro.driver', 'jwt-micro-provider');
    }
}
