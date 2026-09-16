<?php

use App\Http\Controllers\Applicant\EnrollmentController;
use Illuminate\Support\Facades\Route;

Route::post('/enroll', [EnrollmentController::class, 'store'])->name('enroll.store');
Route::get('/enroll/success', [EnrollmentController::class, 'success'])->name('enroll.success');

Route::middleware(['auth'])->group(function () {});

// application tracking routes
Route::get('/track-status', [EnrollmentController::class, 'trackForm'])->name('track.form');
Route::post('/track-status', [EnrollmentController::class, 'checkStatus'])
    ->middleware('throttle:5,1')
    ->name('track.check');

// requires auth.php routes
require __DIR__.'/auth.php';

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