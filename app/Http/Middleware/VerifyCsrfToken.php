<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api1/register',
        'api1/awake',
        'api1/available',
        'api1/rendering',
        'api1/complete',
        'api1/render',
        'api1/status',
        'projects',
        'results',
    ];
}
