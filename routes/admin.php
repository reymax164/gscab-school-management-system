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

    return view('users.admin.dashboard', compact('studentCount', 'staffCount'));
})->name('dashboard');

Route::get('/students', [StudentController::class, 'index'])->name('students');
Route::get('/students/{enrollment}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::patch('/students/{enrollment}', [StudentController::class, 'update'])->name('students.update');
Route::get('/students/{enrollment}', [StudentController::class, 'show'])->name('students.show');
Route::get('/staff', fn () => view('users.admin.staffs'))->name('staffs');
Route::get('/news', fn () => view('users.admin.news'))->name('news');
Route::get('/feedbacks', fn () => view('users.admin.feedbacks'))->name('feedbacks');

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
