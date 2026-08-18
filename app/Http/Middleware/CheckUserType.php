<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    public function handle(Request $request, Closure $next, string $type): Response
    {
        if (!Auth::check() || Auth::user()->user_type !== $type) {
            abort(403, 'Your account type is not allowed in this page.');
        }

        return $next($request);
    }
}