<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Support\Facades\Log;

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
        'api1/downloaded',
        'api1/render',
        'api1/status',
        'api1/new_team',
        'projects',
        'results',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        // Log::info("*** In VerifyCsrfToken with request: " . $request->getUri());

        return parent::handle($request, $next);
    }
}
