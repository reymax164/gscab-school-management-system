<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LogoutController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        // capture the role before logging out
        $role = Auth::user()?->role;

        // perform the logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // redirect
        return redirect('/');
    }
}
