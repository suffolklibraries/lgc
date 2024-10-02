<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get Bearer-Token
        $apiKey =  $request->bearerToken();

        // check that the token is valid
        if (! $apiKey || ! $this->isValid($apiKey)) {
            abort(401, 'Unauthorized');
        }

        return $next($request);
    }

    private function isValid(string $token) : bool
    {
        return $token === config('getcreative.events_api_key');
    }
}
