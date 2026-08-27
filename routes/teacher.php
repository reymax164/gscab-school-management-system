<?php

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/teacher/dashboard');

Route::get('/dashboard', function () {
    return view('users.teacher.dashboard');
})->name('dashboard');

Route::get('/schedule', function (Request $request) {
    $teacher = auth()->user()->teacherProfile;

    $currentYear = now()->year;
    $selectedSy = $request->query('sy', "{$currentYear}-".($currentYear + 1));
    $selectedDay = $request->query('day', 'Today');
    $dayAbbr = $selectedDay === 'Today' ? now()->format('D') : substr($selectedDay, 0, 3);

    $subjectSchedules = $teacher
        ? $teacher->subjectSchedules()
            ->with(['subject', 'classroom', 'classSchedule'])
            ->whereHas('classSchedule', fn ($query) => $query->where('academic_year', $selectedSy))
            ->get()
        : collect();

    $schedules = $subjectSchedules
        ->filter(fn ($subjectSchedule) => in_array($dayAbbr, explode(',', $subjectSchedule->days)))
        ->sortBy('start_time')
        ->map(fn ($subjectSchedule) => (object) [
            'subject' => $subjectSchedule->subject->title ?? 'N/A',
            'grade' => $subjectSchedule->classSchedule->grade_level === 'Kinder'
                ? 'Kindergarten'
                : 'Grade '.($subjectSchedule->classSchedule->grade_level ?? 'N/A'),
            'day' => $subjectSchedule->days,
            'time' => Carbon::parse($subjectSchedule->start_time)->format('g:i A').' - '.Carbon::parse($subjectSchedule->end_time)->format('g:i A'),
            'room' => $subjectSchedule->classroom->name ?? 'N/A',
        ])
        ->values();

    return view('users.teacher.schedule', compact('schedules'));
})->name('schedule');

Route::get('/student_list', function (Request $request) {
    $currentYear = now()->year;
    $defaultSy = "{$currentYear}-".($currentYear + 1);
    $selectedSy = $request->query('sy', $defaultSy);

    $classes = [
        (object) [
            'id' => 1,
            'grade_level' => '5',
            'subject' => (object) ['name' => 'Mathematics'],
            'students_count' => 35,
        ],
        (object) [
            'id' => 2,
            'grade_level' => '8',
            'subject' => (object) ['name' => 'Physics'],
            'students_count' => 40,
        ],
        (object) [
            'id' => 3,
            'grade_level' => '8',
            'subject' => (object) ['name' => 'Biology'],
            'students_count' => 28,
        ],
        (object) [
            'id' => 4,
            'grade_level' => '9',
            'subject' => (object) ['name' => 'Earth Science'],
            'students' => collect(array_fill(0, 30, 'student')),
        ],
    ];

    $classes = collect($classes);

    return view('users.teacher.students', compact('selectedSy', 'classes'));
})->name('students');

Route::get('/student_list/{id}', function ($id) {
    return view('users.teacher.view-student-list', compact('id'));
})->name('view-student-list.show');

Route::get('/student_grades', function () {
    return view('users.teacher.grades');
})->name('grades');
