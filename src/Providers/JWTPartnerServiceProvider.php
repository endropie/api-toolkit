<?php

namespace Endropie\ApiToolkit\Providers;

use Illuminate\Support\ServiceProvider;
use Endropie\ApiToolkit\Providers\Auth\Drivers\JWTPartner;

class JWTPartnerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\Tymon\JWTAuth\Providers\LumenServiceProvider::class);

        $this->app->singleton('http', 'Illuminate\Http\Client\Factory');

        $this->app['auth']->provider(
            'jwt-partner-provider',
            function ($app, array $config) {
                return new JWTPartner();
            }
        );

        $this->app['config']->set('auth.guards.api.driver', 'jwt');
        $this->app['config']->set('auth.guards.api.provider', 'partner');
        $this->app['config']->set('auth.providers.partner.driver', 'jwt-partner-provider');

        $this->app['config']->set('auth.partner.router.prefix', 'auth-partner');
        $this->app['config']->set('auth.partner.http.method', 'post');
        $this->app['config']->set('auth.partner.http.headers', ['accept' => 'Application/json']);
        $this->app['config']->set('auth.partner.http.withToken', true);

        ## Please set config [auth.partner.http.url] in AppServiceProvider
        ## $this->app['config']->set('auth.partner.http.url', 'http://example.com/auth/user');
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(dirname(__DIR__, 2) . '/routes/partner.php');
    }
}
