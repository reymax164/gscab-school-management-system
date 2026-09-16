<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if the authenticated user's role is in the allowed list
        if (! $request->user() || ! in_array($request->user()->role, $roles, true)) {
            // 403 message
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}