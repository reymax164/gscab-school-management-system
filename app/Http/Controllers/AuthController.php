<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        // determine if the form submitted a 'lrn' or an 'email'
        $loginType = $request->has('lrn') ? 'lrn' : 'email';

        // validate the incoming request
        $credentials = $request->validate([
            $loginType => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // attempt to log the user in
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // get the authenticated user
            $user = Auth::user();

            // dynamically redirect based on their user_type 
            return redirect()->intended('/' . $user->user_type . '/dashboard');
        }

        // if authentication fails, send them back with an error on the specific field
        return back()->withErrors([
            $loginType => 'The provided credentials do not match our records.',
        ])->onlyInput($loginType);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}