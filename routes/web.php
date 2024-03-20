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

    // КАРТОЧКА ОБЪЕКТА
    Route::get('/home/card-object', [App\Http\Controllers\ObjectController::class, 'index'])->name('cardObject');
    Route::get('/home/card-object-create', [App\Http\Controllers\ObjectController::class, 'create'])->name('cardObject-create');
    Route::get('/home/card-object/edit', [App\Http\Controllers\ObjectController::class, 'edit'])->name('cardObject-edit');

    Route::get('/card-work-order', [App\Http\Controllers\workOrderController::class, 'index'])->name('workOrder');
});

