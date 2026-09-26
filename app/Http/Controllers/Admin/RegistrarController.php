<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegistrarController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', 'registrar');

        // sorting
        $sortBy = $request->input('sort_by', 'last_name');
        $sortDir = $request->input('sort_dir', 'asc');

        $sortable = ['last_name', 'first_name', 'email'];

        $query->orderBy(in_array($sortBy, $sortable, true) ? $sortBy : 'last_name', $sortDir);

        $registrars = $query->paginate(15)->withQueryString();

        return view('admin.accounts.registrar.index', compact('registrars'));
    }

    public function create(): View
    {
        return view('admin.accounts.registrar.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
        ]);

        // role is always server-enforced; the hidden form field is never trusted
        $registrar = User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'registrar',
        ]);

        return redirect()->route('admin.accounts.registrar.show', $registrar->id)
            ->with('success', 'Registrar account created successfully.');
    }

    public function show(User $registrar): View
    {
        return view('admin.accounts.registrar.show', compact('registrar'));
    }

    public function edit(User $registrar): View
    {
        return view('admin.accounts.registrar.edit', compact('registrar'));
    }

    public function update(Request $request, User $registrar): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($registrar->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
        ]);

        $userData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
        ];

        if (! empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $registrar->update($userData);

        return redirect()->route('admin.accounts.registrar.show', $registrar->id)
            ->with('success', 'Registrar information updated successfully.');
    }
}
