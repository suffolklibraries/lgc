<?php

namespace App\Http\Middleware;

use Closure;
use Statamic\Facades\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustBeLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(! User::current()) {
            return redirect(route('login'));
        }

        return $next($request);
    }
}
