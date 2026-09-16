<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class StudentAuthController extends Controller
{
    public function authenticate(Request $request): RedirectResponse
    {
        // validate
        $credentials = $request->validate([
            'lrn' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // query the users table for LRN
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/student/dashboard');
        }

        // fallback
        return back()->withErrors([
            'lrn' => 'The provided LRN or password is incorrect.',
        ])->onlyInput('lrn');
    }
}