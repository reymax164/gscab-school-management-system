<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Applicant\EnrollmentController;

Route::middleware(['auth'])->group(function () {
    Route::get('/enrollment/apply', [EnrollmentController::class, 'create'])->name('enrollment.create');
    Route::post('/enrollment/apply', [EnrollmentController::class, 'store'])->name('enrollment.store');
});

require __DIR__.'/auth.php';

Route::get('/login', function () {
    return 'Please submit your login form here.';
})->name('login');

Route::get('/', function () {
  return view('guest.index');
})->name('home');

Route::get('news', function () {
  return view('guest.news');
})->name('news');

Route::get('/about', function () {
  return view('guest.about');
})->name('about');

Route::get('/contact', function () {
  return view('guest.contact');
})->name('contact');

Route::get('/faqs', function () {
  return view('guest.faqs');
})->name('faqs');