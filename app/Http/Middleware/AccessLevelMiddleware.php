<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AccessLevelMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$levels)
    { //...(spread operator used to accept multiple argument)

        if (!Auth::user() || !in_array(Auth::user()->access_level, $levels)) {
            return redirect('/')->with('error', 'Access denied!'); //Access denied
        }

        return $next($request);
    }
}
