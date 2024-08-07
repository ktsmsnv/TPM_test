<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\CardObjectMain;
use App\Models\CardWorkOrder;
use App\Models\CardObjectServices;

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
    // Маршрут для формы регистрации пользователей, доступной только администратору
    Route::middleware(['can:register-users'])->group(function () {
        Route::get('/register', [App\Http\Controllers\RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [App\Http\Controllers\RegisterController::class, 'register']);

        // Добавляем маршрут для получения списка пользователей LDAP
        Route::get('/ldap-users', [App\Http\Controllers\RegisterController::class, 'getLdapUsers']);
    });

        // обновление данных профиля
        Route::put('/home/profile/update', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('profile.update');
        // изменение пароля профиля
        Route::put('/home/profile/change-password', [App\Http\Controllers\HomeController::class, 'changePassword'])->name('profile.change-password');

    Route::get('/profile/notifications',  [App\Http\Controllers\HomeController::class, 'showNotifications'])->name('profile.notifications');
    Route::post('/profile/notifications/{id}/read', [App\Http\Controllers\HomeController::class, 'markAsRead'])->name('notifications.read');
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
        Route::get('/get-work-orders',  [App\Http\Controllers\workOrderController::class, 'index'])->name('get-work-orders');
        Route::post('/delete-cardWorkOrder', [App\Http\Controllers\workOrderController::class,'deleteWorkOrder'])->name('delete-cardWorkOrder');

        //----РЕЕСТР ГРАФИКОВ----------------------------------------------------------------------------------------------------------------------------
        Route::get('/pageReestrGraph', [App\Http\Controllers\GraphController::class, 'reestrGraphView'])->name('reestr-Graph');
        // Передача данных таблиц в реестр bootstraptable
        Route::get('/get-cardGraph',  [App\Http\Controllers\GraphController::class, 'getCardGraph'])->name('get-cardGraph');
        Route::post('/delete-cardGraph', [App\Http\Controllers\GraphController::class,'deleteCardGraph'])->name('delete-cardGraph');
        //-----------------------------------------------------------------------------------------------------------------------------------------------
        //----РЕЕСТР КАЛЕНДАРЕЙ--------------------------------------------------------------------------------------------------------------------------
        Route::get('/pageReestrCalendar', [App\Http\Controllers\CalendarController::class, 'view'])->name('reestr-Calendar');
        // Передача данных таблиц в реестр bootstraptable
        Route::get('/get-cardCalendar',  [App\Http\Controllers\CalendarController::class, 'reestrCalendarView'])->name('get-cardCalendar');
        Route::post('/delete-cardCalendar', [App\Http\Controllers\CalendarController::class,'deleteCardCalendar'])->name('delete-cardCalendar');
        //-----------------------------------------------------------------------------------------------------------------------------------------------


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
    Route::get('/reestr-work-orders/card-work-order/{id}', [App\Http\Controllers\WorkOrderController::class, 'show'])->name('workOrder.show');

    Route::post('/create-work-order', [App\Http\Controllers\WorkOrderController::class, 'create'])->name('create-work-order');
    Route::post('/endWorkOrder',  [App\Http\Controllers\WorkOrderController::class, 'endWorkOrder'])->name('endWorkOrder');

   Route::get('/download-pdf/{id}', [App\Http\Controllers\WorkOrderController::class, 'downloadPDF'])->name('downloadPDF');
   // Route::get('/download-word-document/{id}', [App\Http\Controllers\WorkOrderController::class, 'downloadPdfDocument'])->name('downloadWordDocument');


    // ----------------------------------------------------------------------------------------------------------------



    //---------------------------- КАРТОЧКА ГРАФИКА -------------------------------------------------------------------------------------------------
        Route::get('/pageReestrGraph/card-graph/{id}', [App\Http\Controllers\GraphController::class, 'index'])->name('cardGraph');
        //СОЗДАНИЕ новой карточки графика TPM
        Route::get('/pageReestrGraph/card-graph-create', [App\Http\Controllers\GraphController::class, 'createGraphPage'])->name('cardGraph-create');
        Route::post('/pageReestrGraph/card-graph-create', [App\Http\Controllers\GraphController::class, 'createGraphPage'])->name('cardGraph-create');

        Route::post('/save-cardGraph-data/{id}', [App\Http\Controllers\GraphController::class, 'saveCardGraph'])->name('cardGraph-create-save');
        // РЕДАКТИРОВАНИЕ существующей карточки графика TPM
        Route::get('/pageReestrGraph/card-graph/edit/{id}', [App\Http\Controllers\GraphController::class, 'edit'])->name('cardGraph-edit');
        Route::post('/edit-card-graph/save/{id}', [App\Http\Controllers\GraphController::class, 'editSave'])->name('cardGraph-editSave');
        Route::post('/archiveGraphDateButt',  [App\Http\Controllers\GraphController::class, 'archiveGraphDateButt'])->name('archiveGraphDateButt');

        // Маршрут для получения карточек объектов к карточке графика по виду инфраструктуры
        Route::get('/get-all-card-objects', [App\Http\Controllers\GraphController::class, 'getAllCardObjects']);
        Route::post('/add-card-objects-to-graph', [App\Http\Controllers\GraphController::class, 'addCardObjectsToGraph'])->name('addCardObjectsToGraph');


        Route::get('/download-graph/{id}', [App\Http\Controllers\GraphController::class, 'downloadGraph'])->name('downloadGraph');
    // -----------------------------------------------------------------------------------------------------------------------------------------------


    // ---------------------------- КАРТОЧКА КАЛЕНДАРЯ ----------------------------------------------------------------
        Route::get('/pageReestrCalendar/card-calendar/{id}', [App\Http\Controllers\CalendarController::class, 'index'])->name('cardCalendar');

        Route::get('/card-calendar-create/{id}', [App\Http\Controllers\CalendarController::class, 'create'])->name('card-calendar.create');
        Route::post('/card-calendar-store', [App\Http\Controllers\CalendarController::class, 'store'])->name('card-calendar.store');
        // РЕДАКТИРОВАНИЕ существующей карточки графика TPM
        Route::get('/pageReestrGraph/card-calendar/edit/{id}', [App\Http\Controllers\CalendarController::class, 'edit'])->name('cardCalendar-edit');
        Route::post('/edit-card-calendar/save/{id}', [App\Http\Controllers\CalendarController::class, 'editSave'])->name('cardCalendar-editSave');

        Route::post('/archiveCalendarDateButt',  [App\Http\Controllers\CalendarController::class, 'archiveCalendarDateButt'])->name('archiveCalendarDateButt');

   // Route::get('/download-calendar/{id}', [App\Http\Controllers\CalendarController::class, 'downloadCalendar'])->name('downloadCalendar');
    Route::post('/download-calendar/{id}', [App\Http\Controllers\CalendarController::class, 'downloadCalendar'])->name('downloadCalendar');

    // ----------------------------------------------------------------------------------------------------------------


// routes/web.php
//    Route::get('/send-test-email', function () {
//        $user = \App\Models\User::first(); // Получаем первого пользователя из базы данных
//        try {
//            if ($user) {
//                $workOrder = CardWorkOrder::first(); // Получаем первый заказ-наряд
//                $object = CardObjectMain::first(); // Получаем первый объект
//                $service = CardObjectServices::first(); // Получаем первую услугу
//                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\WorkOrderNotification($workOrder, $object, $service));
//                return 'Письмо отправлено на адрес: ' . $user->email;
//            } else {
//                return 'Нет доступных пользователей с адресом электронной почты.';
//            }
//        } catch (\Exception $e) {
//            return 'Ошибка: ' . $e->getMessage();
//        }
//    });


    Route::get('/send-test-mail',  [App\Http\Controllers\MailTestController::class, 'sendTestEmail']);

    Route::get('/sendmail',  [App\Http\Controllers\MailTestController::class, 'sendEmail']);

});

