<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

// Маршрут для главной страницы входа доступен только неаутентифицированным пользователям
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

// Все маршруты доступны только аутентифицированным пользователям
Route::middleware(['auth'])->group(function () {
    //ЛИЧНЫЙ КАБИНЕТ
    Route::get('/home/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');
    Route::put('/home/profile/update', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('profile.update');
    Route::put('/home/profile/change-password', [App\Http\Controllers\HomeController::class, 'changePassword'])->name('profile.change-password');

    // РЕЕСТРЫ
    //Реестр объектов
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    //Реестр заказов
    Route::get('/reestr-work-orders', [App\Http\Controllers\HomeController::class, 'reestrWorkOrdersView'])->name('reestr-workOrders');

    //Реестр графиков
    Route::get('/pageReestrGraph', [App\Http\Controllers\pageReestrGraphController::class, 'reestrGraphView'])->name('reestr-Graph');
    //Route::get('/get-reestrGraph-details/{id}', 'App\Http\Controllers\pageReestrGraphController@getReestrGraphDetails')->name('get-reestrGraph-details');

    //Реестр календарей
    Route::get('/pageReestrCalendar', [App\Http\Controllers\pageReestrCalendarController::class, 'reestrCalendarView'])->name('reestr-Calendar');



    // КАРТОЧКА ОБЪЕКТА
    Route::get('/home/card-object/{id}', [App\Http\Controllers\ObjectController::class, 'index'])->name('cardObject');


    Route::get('/getImage/{id}', [App\Http\Controllers\ObjectController::class, 'getImage'])->name('getImage');
    Route::get('/download-document/{id}', [App\Http\Controllers\ObjectController::class, 'downloadDocument'])->name('downloadDocument');


    Route::get('/home/card-object-create', [App\Http\Controllers\ObjectController::class, 'create'])->name('cardObject-create');
    Route::post('/save-card-data', [App\Http\Controllers\ObjectController::class, 'saveData'])->name('cardObject-create-save');
    Route::get('/home/card-object/edit', [App\Http\Controllers\ObjectController::class, 'edit'])->name('cardObject-edit');

    // КАРТОЧКА ЗАКАЗ-НАРЯДА
    Route::get('/reestr-work-orders/card-work-order', [App\Http\Controllers\workOrderController::class, 'index'])->name('workOrder');

    //КАРТОЧКА ГРАФИКА
    Route::get('/pageReestrGraph/card-graph', [App\Http\Controllers\GraphController::class, 'index'])->name('cardGraph');

    //КАРТОЧКА КАЛЕНДАРЯ
    Route::get('/pageReestrCalendar/card-calendar', [App\Http\Controllers\CalendarController::class, 'index'])->name('cardCalendar');
});

