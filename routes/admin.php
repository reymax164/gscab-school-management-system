<?php

use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\ClassScheduleController;
use App\Http\Controllers\Admin\SectioningController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\SystemSettingController;
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
        Route::get('/', fn () => view('admin.accounts.teachers.index'))->name('index');
        Route::get('/create', fn () => view('admin.accounts.teachers.create'))->name('create');
        Route::get('/{teacher}', fn () => view('admin.accounts.teachers.show'))->name('show');
        Route::get('/{teacher}/edit', fn () => view('admin.accounts.teachers.edit'))->name('edit');
    });

    // registrar
    Route::prefix('registrar')->name('registrar.')->group(function () {
        Route::get('/', fn () => view('admin.accounts.registrar.index'))->name('index');
        Route::get('/create', fn () => view('admin.accounts.registrar.create'))->name('create');
        Route::get('/{registrar}', fn () => view('admin.accounts.registrar.show'))->name('show');
        Route::get('/{registrar}/edit', fn () => view('admin.accounts.registrar.edit'))->name('edit');
    });

    // cashier
    Route::prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/', fn () => view('admin.accounts.cashier.index'))->name('index');
        Route::get('/create', fn () => view('admin.accounts.cashier.create'))->name('create');
        Route::get('/{cashier}', fn () => view('admin.accounts.cashier.show'))->name('show');
        Route::get('/{cashier}/edit', fn () => view('admin.accounts.cashier.edit'))->name('edit');
    });
});

// HUB-AND-SPOKE RESOURCES
Route::resource('subjects', SubjectController::class);
Route::resource('classrooms', ClassroomController::class);
Route::resource('system-settings', SystemSettingController::class);
Route::resource('class-schedules', ClassScheduleController::class);

// SECTIONING
Route::get('class-schedules/{class_schedule}/sectioning', [SectioningController::class, 'index'])
    ->name('class-schedules.sectioning');

Route::post('class-schedules/{class_schedule}/sectioning', [SectioningController::class, 'store'])
    ->name('class-schedules.sectioning.store');

Route::delete('class-schedules/{class_schedule}/sectioning/{student}', [SectioningController::class, 'destroy'])
    ->name('class-schedules.sectioning.destroy');
