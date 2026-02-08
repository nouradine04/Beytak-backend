<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (auth('api')->check()) {
            // Pour les API, renvoyer 403 JSON; pour le web, on pourrait rediriger.
            return response()->json(['message' => 'Already authenticated.'], 403);
        }

        return $next($request);
    }
}
