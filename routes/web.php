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

    // ---------------------------- ЛИЧНЫЙ КАБИНЕТ --------------------------------------------------------------------
        Route::get('/home/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');
        // обновление данных профиля
        Route::put('/home/profile/update', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('profile.update');
        // изменение пароля профиля
        Route::put('/home/profile/change-password', [App\Http\Controllers\HomeController::class, 'changePassword'])->name('profile.change-password');
    // ----------------------------------------------------------------------------------------------------------------

    // ---------------------------- РЕЕСТРЫ ----------------------------------------------------------------------------
        //Реестр объектов
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        // Передача данных таблиц в реестр bootstraptable
        Route::get('/get-objects',  [App\Http\Controllers\HomeController::class, 'getObjects'])->name('get-objects');
        // Копия карточки объекта
        Route::post('/copy-cardObject', [App\Http\Controllers\HomeController::class, 'copyObject'])->name('copy-cardObject');


    Route::post('/delete-cardObject', [App\Http\Controllers\HomeController::class,'deleteObject'])->name('delete-cardObject');
        //Реестр заказов
        Route::get('/reestr-work-orders', [App\Http\Controllers\HomeController::class, 'reestrWorkOrdersView'])->name('reestr-workOrders');
        //Реестр графиков
        Route::get('/pageReestrGraph', [App\Http\Controllers\pageReestrGraphController::class, 'reestrGraphView'])->name('reestr-Graph');
        //Route::get('/get-reestrGraph-details/{id}', 'App\Http\Controllers\pageReestrGraphController@getReestrGraphDetails')->name('get-reestrGraph-details');
        //Реестр календарей
        Route::get('/pageReestrCalendar', [App\Http\Controllers\pageReestrCalendarController::class, 'reestrCalendarView'])->name('reestr-Calendar');
    // ----------------------------------------------------------------------------------------------------------------

    // ---------------------------- КАРТОЧКА ОБЪЕКТА ------------------------------------------------------------------
        Route::get('/home/card-object/{id}', [App\Http\Controllers\ObjectController::class, 'index'])->name('cardObject');
        // передача изображения и документов карточки объекта
        Route::get('/getImage/{id}', [App\Http\Controllers\ObjectController::class, 'getImage'])->name('getImage');
        Route::get('/download-document/{id}', [App\Http\Controllers\ObjectController::class, 'downloadDocument'])->name('downloadDocument');
        // СОЗДАНИЕ новой карточки объекта
        Route::get('/home/card-object-create', [App\Http\Controllers\ObjectController::class, 'create'])->name('cardObject-create');
        Route::post('/save-card-data', [App\Http\Controllers\ObjectController::class, 'saveData'])->name('cardObject-create-save');
        // РЕДАКТИРОВАНИЕ существующей карточки объекта
        Route::get('/home/card-object/edit/{id}', [App\Http\Controllers\ObjectController::class, 'edit'])->name('cardObject-edit');
        Route::post('/edit-card-object-save/{id}', [App\Http\Controllers\ObjectController::class, 'editSave'])->name('cardObject-editSave');
        // удаление обслуживания у карточки
        Route::delete('/delete-service/{cardId}/{serviceId}', [App\Http\Controllers\ObjectController::class, 'deleteService'])->name('delete.service');

        Route::post('/update-type-checked', [App\Http\Controllers\ObjectController::class, 'updateChecked'])->name('update-type-checked');

    // ----------------------------------------------------------------------------------------------------------------

    // ---------------------------- КАРТОЧКА ЗАКАЗ-НАРЯДА -------------------------------------------------------------
    Route::get('/reestr-work-orders/card-work-order', [App\Http\Controllers\workOrderController::class, 'index'])->name('workOrder');
    Route::get('/reestr-work-orders/card-work-order/{id}', [App\Http\Controllers\WorkOrderController::class, 'show'])->name('workOrder.show');

    Route::post('/create-work-order', [App\Http\Controllers\WorkOrderController::class, 'create'])->name('create-work-order');

    // ----------------------------------------------------------------------------------------------------------------

    // ---------------------------- КАРТОЧКА ГРАФИКА ------------------------------------------------------------------
        Route::get('/pageReestrGraph/card-graph', [App\Http\Controllers\GraphController::class, 'index'])->name('cardGraph');
    // ----------------------------------------------------------------------------------------------------------------

    // ---------------------------- КАРТОЧКА КАЛЕНДАРЯ ----------------------------------------------------------------
        Route::get('/pageReestrCalendar/card-calendar', [App\Http\Controllers\CalendarController::class, 'index'])->name('cardCalendar');
    // ----------------------------------------------------------------------------------------------------------------
});

