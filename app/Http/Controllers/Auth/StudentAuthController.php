<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student; // Make sure your Student model is imported
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Needed to verify the password manually
use Illuminate\Http\RedirectResponse;

class StudentAuthController extends Controller
{
    public function authenticate(Request $request): RedirectResponse
    {
        $request->validate([
            'lrn' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // 1. Look up the student record by LRN and eager load the associated user
        $student = Student::where('lrn', $request->lrn)->with('user')->first();

        // 2. Verify the student exists, has a connected user account, and the password matches
        if ($student && $student->user && Hash::check($request->password, $student->user->password)) {
            
            // 3. Manually log the user in
            Auth::login($student->user);
            
            $request->session()->regenerate();

            return redirect()->intended('/student/dashboard');
        }

        // 4. If any check fails, kick them back with a generic error
        return back()->withErrors([
            'lrn' => 'The provided LRN or password is incorrect.',
        ])->onlyInput('lrn');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/student/login');
    }
}