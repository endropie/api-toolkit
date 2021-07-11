<?php

namespace Endropie\ApiToolkit\Traits;

use Illuminate\Auth\Authenticatable;

trait AuthenticatableMicro
{
    use Authenticatable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
