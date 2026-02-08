<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:admin') or 'role:admin|agence'
     */
    public function handle(Request $request, Closure $next, string $roles = null)
    {
        /** @var \App\Models\User|null $user */
        $user = auth('api')->user();

        if (! $user) {
            return response()->json(['error' => 'unauthenticated'], 401);
        }

        if ($roles) {
            $allowed = explode('|', $roles);
            if (! in_array($user->role, $allowed)) {
                return response()->json(['error' => 'forbidden'], 403);
            }
        }

        return $next($request);
    }
}
