<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user()->load(['teacher', 'student']);

        return view('auth.profile', ['user' => $user]);
    }

    public function updatePhoto(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', Rule::dimensions()->ratio(1 / 1)],
        ]);

        $path = $validated['photo']->store('profile-photos');

        $request->user()->update(['profile_photo_path' => $path]);

        return back()->with('status', 'profile-photo-updated');
    }
}
