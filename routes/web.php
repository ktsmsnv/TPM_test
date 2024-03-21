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
    Route::get('/pageReestrGraph', [App\Http\Controllers\pageReestrGraphController::class, 'reestrGraphView'])->name('reestr-Graph');
//    Route::get('/get-reestrGraph-details/{id}', 'App\Http\Controllers\pageReestrGraphController@getReestrGraphDetails')->name('get-reestrGraph-details');

        //Реестр календарей
    Route::get('/pageReestrCalendar', 'App\Http\Controllers\pageReestrCalendarController@index')->name('pageReestrCalendar');
    Route::get('/pageReestrCalendar', [App\Http\Controllers\pageReestrCalendarController::class, 'reestrCalendarView'])->name('reestr-Calendar');

    // КАРТОЧКИ
    Route::get('/home/card-object', [App\Http\Controllers\ObjectController::class, 'index'])->name('cardObject');
    Route::get('/reestr-work-orders/card-work-order', [App\Http\Controllers\workOrderController::class, 'index'])->name('workOrder');

    //Карточка Графика
    Route::get('/pageReestrGraph/card-graph', [App\Http\Controllers\GraphController::class, 'index'])->name('cardGraph');

    //Карточка Календаря
    Route::get('/pageReestrCalendar/card-calendar', [App\Http\Controllers\CalendarController::class, 'index'])->name('cardCalendar');
});

