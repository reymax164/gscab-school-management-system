<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.auth')->name('login');
    Route::view('/enroll', 'auth.enroll')->name('enroll');
});