<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddPassportClientToRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->isTokenRoute($request) && $request->input('grant_type') === 'password') {
        }

        return $next($request);
    }

    protected function isTokenRoute(Request $request): bool
    {
        // remove first / character from both routes
        $authRoute = ltrim(route('passport.token', [], false), '/');
        $currentRoute = ltrim($request->path(), '/');

        return $authRoute === $currentRoute;
    }
}
