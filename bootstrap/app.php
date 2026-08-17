<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',

        then: function() {
            // admin routes -> /admin/*
            Route::middleware(['web'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            // teacher routes -> /teacher/*
            Route::middleware(['web'])
                ->prefix('teacher')
                ->name('teacher.')
                ->group(base_path('routes/teacher.php'));

            // student routes -> /student/*
            Route::middleware(['web'])
                ->prefix('student')
                ->name('student.')
                ->group(base_path('routes/student.php'));

            // registrar routes -> /registrar/*
            Route::middleware(['web'])
                ->prefix('registrar')
                ->name('registrar.')
                ->group(base_path('routes/registrar.php'));

            // cashier routes -> /cashier/*
            Route::middleware(['web'])
                ->prefix('cashier')
                ->name('cashier.')
                ->group(base_path('routes/cashier.php'));

            // guest routes
            Route::middleware(['web'])
                ->name('auth.')
                ->group(base_path('routes/auth.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
