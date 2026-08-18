<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckUserType;
use Illuminate\Support\Facades\Auth;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',

        then: function() {
            // admin routes -> /admin/*
            Route::middleware(['web', 'auth', CheckUserType::class.':admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            // teacher routes -> /teacher/*
            Route::middleware(['web', 'auth', CheckUserType::class.':teacher'])
                ->prefix('teacher')
                ->name('teacher.')
                ->group(base_path('routes/teacher.php'));

            // student routes -> /student/*
            Route::middleware(['web', 'auth', CheckUserType::class.':student'])
                ->prefix('student')
                ->name('student.')
                ->group(base_path('routes/student.php'));

            // registrar routes -> /registrar/*
            Route::middleware(['web', 'auth', CheckUserType::class.':registrar'])
                ->prefix('registrar')
                ->name('registrar.')
                ->group(base_path('routes/registrar.php'));

            // cashier routes -> /cashier/*
            Route::middleware(['web', 'auth', CheckUserType::class.':cashier'])
                ->prefix('cashier')
                ->name('cashier.')
                ->group(base_path('routes/cashier.php'));

            // guest / Auth routes
            Route::middleware(['web'])
                ->name('auth.')
                ->group(base_path('routes/auth.php'));
        },
    
    )
    ->withMiddleware(function (Middleware $middleware): void {
      // redirect unauthenticated users
      $middleware->redirectGuestsTo(function (Request $request) {
          
          // to student login
          if ($request->is('student*')) {
              return route('auth.login');
          }
          
          // to the staff login
          return route('auth.staff-login');
      });

      // redirect authenticated users
      $middleware->redirectUsersTo(function (Request $request) {
        // gets the authenticated user
        $user = Auth::user();
        
        // redirect to their dashboard
        return '/' . $user->user_type . '/dashboard';
      });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();