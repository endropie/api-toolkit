<?php

namespace Endropie\ApiToolkit\Auth;

use App\Models\User;
use Throwable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMicro implements UserProvider
{
    public function retrieveByToken($identifier, $token)
    {
        throw new Throwable('Method not implemented.');
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        throw new Throwable('Method not implemented.');
    }

    public function retrieveById($identifier)
    {
        return $this->getMemberInstance($identifier);
    }

    public function retrieveByCredentials(array $credentials)
    {
        return $this->getMemberInstance($credentials);
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return true;
    }

    private function getMemberInstance($credentials)
    {

        return tap(new User(), function ($user) use ($credentials) {
            $user->id = $credentials;
            $payload = JWTAuth::parseToken()->getPayload();
            // $user->ability = $payload['ability'];

            dd('auth', $payload, auth());
        });
    }
}
