<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check() || ! in_array($request->user()->role, $roles, true)) {
            $rawRole = $request->user()?->role ?? 'guest';

            $roleLabel = ucfirst(Str::plural($rawRole));

            abort(403, "{$roleLabel} are not allowed on this page.");
        }

        return $next($request);
    }
}
