<?php

namespace Endropie\ApiToolkit\Traits;

use Illuminate\Auth\Authenticatable;

trait AuthenticatablePartner
{
    use Authenticatable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['attributes' => $this->toArray()];
    }

    public function setPartnerAttibutes($response)
    {
        $this->setRawAttributes($response);
    }

    public function setClaimAttibutes()
    {
        $attributes = (array) app('tymon.jwt.auth')->parseToken()->getPayload()->get('attributes') ?? [];
        $this->setRawAttributes($attributes);
    }

    public function getPartnerResponse()
    {
        $url = config('auth.partner.retrieve.url');
        $method = config('auth.partner.retrieve.method', 'GET');
        $headers = config('auth.partner.retrieve.headers', []);

        if (!$url) abort(500, 'Partner url is not undefined. Please set config [auth.partner.retrieve.url]');

        $response = config('auth.partner.retrieve.withToken')
            ? app('http')->withHeaders($headers)->withToken(request('token'))->{strtolower($method)}($url)
            : app('http')->withHeaders($headers)->{strtolower($method)}($url);

        $response->throw();

        return $response->json();
    }
}
