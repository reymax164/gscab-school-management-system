<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', 'dashboard');

Route::get('/dashboard', function () {
  return view('users.teacher.dashboard');
})->name('dashboard');

Route::get('/schedule', function () {
  return view('users.teacher.schedule');
})->name('schedule');

Route::get('/student_list', function (Request $request) {
  // Dummy data
  // school year
  $currentYear = now()->year;
  $defaultSy = "{$currentYear}-" . ($currentYear + 1);
  $selectedSy = $request->query('sy', $defaultSy);

  // fake students
  $classes = collect([
    (object) [
      'id' => 1,
      'grade_level' => '9',
      'section_name' => null,
      'subject' => (object) [
          'name' => 'English',
      ],
      'students' => collect()->pad(35, null),
      'students_count' => 35,
    ],
    (object) [
      'id' => 2,
      'grade_level' => '10',
      'section_name' => null,
      'subject' => (object) [
          'name' => 'Mathematics',
      ],
      'students' => collect()->pad(28, null),
      'students_count' => 28,
    ],
  ]);

  // pass to the view
  return view('users.teacher.students', compact('selectedSy', 'classes'));
})->name('students');

Route::get('/student_list/{id}', function ($id) {
  // Dummy data
  $classesData = [
    
    1 => (object) [
      'id' => 1,
      'subject' => (object) ['name' => 'English'],
      'grade_level' => '9',
      'section_name' => 'A',
      'school_year' => '2026-2027',
      'students' => collect([
        (object) [
          'lrn' => '107440090001',
          'last_name' => 'Dela Cruz',
          'first_name' => 'Juan',
          'middle_name' => 'Santos',
          'gender' => 'Male',
          'guardian' => 'Ana Dela Cruz',
          'mobile_no' => '09170000000',
        ],
      ]),
    ],
    
    2 => (object) [
      'id' => 2,
      'subject' => (object) ['name' => 'Mathematics'],
      'grade_level' => '10',
      'section_name' => null,
      'school_year' => '2026-2027',
      'students' => collect([
        (object) [
          'lrn' => '107440090041',
          'last_name' => 'Evangelio',
          'first_name' => 'Christopher',
          'middle_name' => 'Rodriguez',
          'gender' => 'Male',
          'guardian' => 'Maria Evangelio',
          'mobile_no' => '09123456789',
        ],
        (object) [
          'lrn' => '107440090104',
          'last_name' => 'Bautista',
          'first_name' => 'Joy',
          'middle_name' => 'Santiago',
          'gender' => 'Female',
          'guardian' => 'Jose Bautista',
          'mobile_no' => '09987654321',
        ],
      ]),
    ],
  ];

  // find requested class or  null
  $class = $classesData[$id] ?? null;

  return view('users.teacher.view-student-list', compact('class'));
})->name('view-student-list.show');

Route::get('/student_grades', function () {
  return view('users.teacher.grades');
})->name('grades');