<?php

namespace Endropie\ApiToolkit\Auth;

class JWTPartner
{

    public static function router($prefix = 'auth-partner', $router = null)
    {
        $router = $router ?: app('router');

        $router->group(['prefix' => $prefix], function () use ($router) {
            $router->get('login', function () use ($router) {

                $user = new \App\Models\User();

                $response  = $user->getPartnerResponse();

                $user->setPartnerAttibutes($response);

                if (!$user->getJWTIdentifier()) {
                    abort(401, 'Login Failed. JWT Indentifier undefined.');
                }

                $token =  auth()->login($user);

                return ['token' => $token];
            });
            $router->group(['middleware' => 'auth'], function () use ($router) {
                $router->get('user', function () use ($router) {
                    return ['user' => auth()->user()];
                });
            });
        });
    }
}
