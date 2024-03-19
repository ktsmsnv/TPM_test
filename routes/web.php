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

    // РЕЕСТРЫ
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/reestr-work-orders', [App\Http\Controllers\HomeController::class, 'reestrWorkOrdersView'])->name('reestr-workOrders');

        //Реестр графиков
    Route::get('/pageReestrGraph', 'App\Http\Controllers\pageReestrGraphController@index')->name('pageReestrGraph');
    Route::get('/get-reestrGraph-details/{id}', 'App\Http\Controllers\pageReestrGraphController@getReestrGraphDetails')->name('get-reestrGraph-details');

    // КАРТОЧКИ
        //Карточка Объекта
    Route::get('/card-object', [App\Http\Controllers\ObjectController::class, 'index'])->name('cardObject');

        //Карточка Графика
    Route::get('/card-graph', [App\Http\Controllers\GraphController::class, 'index'])->name('cardGraph');
});

