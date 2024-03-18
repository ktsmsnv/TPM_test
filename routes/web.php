<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

// Маршрут для главной страницы входа доступен только неаутентифицированным пользователям
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });
});

// Все маршруты доступны только аутентифицированным пользователям
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/pageReestrGraph', [App\Http\Controllers\pageReestrGraphController::class, 'index'])->name('pageReestrGraph');
    Route::get('/pageReestrCalendar', [App\Http\Controllers\pageReestrCalendarController::class, 'index'])->name('pageReestrCalendar');
});

