<?php

use App\Http\Controllers\Admin\CashierController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\RegistrarController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SectioningController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\TeacherController;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// DASHBOARD
Route::redirect('/', 'dashboard');

Route::get('/dashboard', function () {
    $studentCount = Student::where('enrollment_status', 'enrolled')->count();
    $staffCount = User::whereIn('role', ['admin', 'registrar', 'cashier', 'teacher'])->count();

    return view('admin.dashboard', compact('studentCount', 'staffCount'));
})->name('dashboard');

Route::get('/staff', fn () => view('admin.staffs'))->name('staffs');
Route::get('/news', fn () => view('admin.news'))->name('news');
Route::get('/feedbacks', fn () => view('admin.feedbacks'))->name('feedbacks');

// ACCOUNTS MANAGEMENT
Route::prefix('accounts')->name('accounts.')->group(function () {

    // students
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('index');
        Route::get('/{enrollment}', [StudentController::class, 'show'])->name('show');
        Route::get('/{enrollment}/edit', [StudentController::class, 'edit'])->name('edit');
        Route::patch('/{enrollment}', [StudentController::class, 'update'])->name('update');
    });

    // teachers
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('index');
        Route::get('/create', [TeacherController::class, 'create'])->name('create');
        Route::post('/', [TeacherController::class, 'store'])->name('store');
        Route::get('/{teacher}', [TeacherController::class, 'show'])->name('show');
        Route::get('/{teacher}/edit', [TeacherController::class, 'edit'])->name('edit');
        Route::patch('/{teacher}', [TeacherController::class, 'update'])->name('update');
    });

    // registrar
    Route::prefix('registrar')->name('registrar.')->group(function () {
        Route::get('/', [RegistrarController::class, 'index'])->name('index');
        Route::get('/create', [RegistrarController::class, 'create'])->name('create');
        Route::post('/', [RegistrarController::class, 'store'])->name('store');
        Route::get('/{registrar}', [RegistrarController::class, 'show'])->name('show');
        Route::get('/{registrar}/edit', [RegistrarController::class, 'edit'])->name('edit');
        Route::patch('/{registrar}', [RegistrarController::class, 'update'])->name('update');
    });

    // cashier
    Route::prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/', [CashierController::class, 'index'])->name('index');
        Route::get('/create', [CashierController::class, 'create'])->name('create');
        Route::post('/', [CashierController::class, 'store'])->name('store');
        Route::get('/{cashier}', [CashierController::class, 'show'])->name('show');
        Route::get('/{cashier}/edit', [CashierController::class, 'edit'])->name('edit');
        Route::patch('/{cashier}', [CashierController::class, 'update'])->name('update');
    });
});

// HUB-AND-SPOKE RESOURCES
Route::resource('subjects', SubjectController::class);
Route::resource('classrooms', ClassroomController::class);
Route::resource('system-settings', SystemSettingController::class);
Route::resource('sections', SectionController::class);

// SECTIONING
Route::get('sections/{section}/sectioning', [SectioningController::class, 'index'])
    ->name('sections.sectioning');

Route::post('sections/{section}/sectioning', [SectioningController::class, 'store'])
    ->name('sections.sectioning.store');

Route::delete('sections/{section}/sectioning/{student}', [SectioningController::class, 'destroy'])
    ->name('sections.sectioning.destroy');
