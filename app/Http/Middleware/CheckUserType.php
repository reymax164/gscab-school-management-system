<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    public function handle(Request $request, Closure $next, string $type): Response
    {
        if (!Auth::check() || $request->user()->user_type !== $type) {
            $rawType = $request->user()?->user_type ?? 'guest';
            
            $roleLabel = ucfirst(Str::plural($rawType));

            abort(403, "{$roleLabel} are not allowed on this page.");
        }

        return $next($request);
    }
}