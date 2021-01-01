<?php

namespace AdminBase\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Security
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var Response $response */
        $response = $next($request);

        $headers = [
            'X-XSS-Protection' => 1,
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'sameorigin'
        ];
        $response->headers->add($headers);

        return $response;
    }
}
