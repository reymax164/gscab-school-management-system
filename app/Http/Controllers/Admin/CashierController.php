<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CashierController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', 'cashier');

        // sorting
        $sortBy = $request->input('sort_by', 'last_name');
        $sortDir = $request->input('sort_dir', 'asc');

        $sortable = ['last_name', 'first_name', 'email'];

        $query->orderBy(in_array($sortBy, $sortable, true) ? $sortBy : 'last_name', $sortDir);

        $cashiers = $query->paginate(15)->withQueryString();

        return view('admin.accounts.cashier.index', compact('cashiers'));
    }

    public function create(): View
    {
        return view('admin.accounts.cashier.create');
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
        $cashier = User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'cashier',
        ]);

        return redirect()->route('admin.accounts.cashier.show', $cashier->id)
            ->with('success', 'Cashier account created successfully.');
    }

    public function show(User $cashier): View
    {
        return view('admin.accounts.cashier.show', compact('cashier'));
    }

    public function edit(User $cashier): View
    {
        return view('admin.accounts.cashier.edit', compact('cashier'));
    }

    public function update(Request $request, User $cashier): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($cashier->id)],
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

        $cashier->update($userData);

        return redirect()->route('admin.accounts.cashier.show', $cashier->id)
            ->with('success', 'Cashier information updated successfully.');
    }
}
