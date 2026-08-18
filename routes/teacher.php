<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/teacher/dashboard');

Route::get('/dashboard', function () {
    return view('users.teacher.dashboard');
})->name('dashboard');

Route::get('/schedule', function () {
    return view('users.teacher.schedule');
})->name('schedule');

Route::get('/student_list', function () {
    return view('users.teacher.students');
})->name('students');

Route::get('/student_list/{id}', function ($id) {
    return view('users.teacher.view-student-list', compact('id'));
})->name('view-student-list.show');

Route::get('/student_grades', function () {
    return view('users.teacher.grades');
})->name('grades');