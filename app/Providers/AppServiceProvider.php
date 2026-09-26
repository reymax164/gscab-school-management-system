<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // scope {registrar}/{cashier} route-model binding to their role to prevent cross-role IDOR
        Route::bind('registrar', fn (string $value) => User::where('role', 'registrar')->findOrFail($value));
        Route::bind('cashier', fn (string $value) => User::where('role', 'cashier')->findOrFail($value));
    }
}
