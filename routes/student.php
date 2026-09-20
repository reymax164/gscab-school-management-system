<?php

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\ScheduleController;

Route::get('/login', function () {
    return 'Please submit your login form here.';
})->name('login');

Route::redirect('/', '/student/dashboard');

Route::get('/dashboard', function () {
    return view('users.student.dashboard');
})->name('dashboard');

// Old schedule route
// Route::get('/schedule', function (Request $request) {
//     $student = Auth::user()?->student;

//     $currentYear = now()->year;
//     $selectedSy = $request->query('sy', "{$currentYear}-".($currentYear + 1));
//     $selectedDay = $request->query('day', 'Today');
//     $dayAbbr = $selectedDay === 'Today' ? now()->format('D') : substr($selectedDay, 0, 3);

//     $section = $student?->classSchedules()
//         ->wherePivot('status', 'enrolled')
//         ->where('academic_year', $selectedSy)
//         ->with(['subjectSchedules.subject', 'subjectSchedules.teacher.user', 'subjectSchedules.classroom'])
//         ->first();

//     $grade = $section?->grade_level;

//     $schedules = collect($section?->subjectSchedules ?? [])
//         ->filter(fn ($subjectSchedule) => in_array($dayAbbr, explode(',', $subjectSchedule->days)))
//         ->sortBy('start_time')
//         ->map(fn ($subjectSchedule) => (object) [
//             'subject' => $subjectSchedule->subject->title ?? 'N/A',
//             'day' => $subjectSchedule->days,
//             'time' => Carbon::parse($subjectSchedule->start_time)->format('g:i A').' - '.Carbon::parse($subjectSchedule->end_time)->format('g:i A'),
//             'room' => $subjectSchedule->classroom->name ?? 'N/A',
//             'teacher' => ($subjectSchedule->teacher->user->last_name ?? 'N/A').(isset($subjectSchedule->teacher->user) ? ', '.$subjectSchedule->teacher->user->first_name : ''),
//         ])
//         ->values();

//     return view('users.student.schedule', compact('grade', 'schedules'));
// })->name('schedule');

// new schedule route using ScheduleController
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');

Route::get('/grades', function () {
    return view('users.student.grades');
})->name('grades');

Route::get('/balance', function () {
    return view('users.student.balance');
})->name('balance');

Route::get('/feedback', function () {
    return view('users.student.feedback');
})->name('feedback');
