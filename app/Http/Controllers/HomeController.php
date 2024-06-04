<?php

namespace App\Http\Controllers;


use App\Models\CardWorkOrder;
use App\Models\Notification;
use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\CardGraph;
use App\Models\CardCalendar;
use App\Models\CardObjectMain;
use App\Models\CardObjectMainDoc;
use App\Models\CardObjectServices;
use App\Models\CardObjectServicesTypes;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    // ------------------ ОТОБРАЖЕНИЕ РЕЕСТРА ОБЪЕКТОВ ------------------
    public function index() {
        $objects = CardObjectMain::with('services')->get();
        $breadcrumbs = Breadcrumbs::generate('home');
        return view('home', compact('breadcrumbs', 'objects'));
    }
    // ------------------ ОТОБРАЖЕНИЕ РЕЕСТРА ОБЪЕКТОВ -> ПОЛУЧЕНИЕ ЗАПИСЕЙ И ПЕРЕДАЧА В ШАБЛОН ------------------
    public function getObjects() {
        $user = Auth::user();
        $role = $user->role;

        // Получение объектов с соответствующими услугами
        if ($role === 'executor') {
            $objects = CardObjectMain::whereHas('services', function ($query) use ($user) {
                $query->where('performer', $user->name);
            })->with(['services', 'workOrders', 'calendar'])->get()->toArray(); // Преобразуем коллекцию в массив;
        } elseif ($role === 'responsible') {
            $objects = CardObjectMain::whereHas('services', function ($query) use ($user) {
                $query->where('responsible', $user->name);
            })->with(['services', 'workOrders', 'calendar'])->get()->toArray(); // Преобразуем коллекцию в массив;
        } else { // Для ролей curator и admin выводим все объекты
            $objects = CardObjectMain::with(['services', 'workOrders', 'calendar'])->get()->toArray(); // Преобразуем коллекцию в массив;
        }

        // Создаем массив для хранения всех данных
        $formattedObjects = [];
        // Проходимся по каждому объекту и выбираем все поля
        foreach ($objects as $object) {
            $workOrderLink = '';
            if (!empty($object['workOrders'])) {
                foreach ($object['workOrders'] as $workOrder) {
                    $workOrderLink .= '<a href="' . route('workOrder.show', ['id' => $workOrder['_id']]) .
                        '" target="_blank" class="tool-tip" title="открыть карточку заказ-наряда">' . 'открыть' . '</a>';
                }
            }
            $calendarLink = '';
            if (!empty($object['calendar'])) {
                foreach ($object['calendar'] as $calendar) {
                    $calendarLink .= '<a href="' . route('cardCalendar', ['id' => $calendar['_id']]) .
                        '" target="_blank" class="tool-tip" title="открыть карточку календарь">' . 'открыть' . '</a>';
                }
            }

            // Фильтруем сервисы, исключая те, у которых checked = true
            $filteredServices = [];
            // Фильтруем сервисы, исключая те, у которых checked = true
            $filteredServices = collect($object['services'])->filter(function($service) {
                // Фильтруем массив, оставляя только сервисы, у которых свойство 'checked' равно false
                return !$service['checked'];
            });

            $formattedObject = [
                'id' => $object['_id'], // Обратите внимание на изменение здесь
                'infrastructure' => $object['infrastructure'],
                'name' => $object['name'],
                'curator' => $object['curator'],
                'number' => $object['number'],
                'location' => $object['location'],
                'date_usage' => $object['date_usage'],
                'date_usage_end' => $object['date_usage_end'],
                'date_cert_end' => $object['date_cert_end'],
                'calendar' => $calendarLink,
                'services' => $filteredServices->map(function($service) {
                    $workOrder = CardWorkOrder::where('service_id', $service['_id'])->first(); // Получаем соответствующий заказ-наряд
                    return [
                        'service_type' => $service['service_type'],
                        'short_name' => $service['short_name'],
                        'performer' => $service['performer'],
                        'responsible' => $service['responsible'],
                        'frequency' => $service['frequency'],
                        'prev_maintenance_date' => $service['prev_maintenance_date'],
                        'planned_maintenance_date' => $service['planned_maintenance_date'],
                        'calendar_color' => $service['calendar_color'],
                        'consumable_materials' => $service['consumable_materials'],
                        'work_order' => $workOrder ? route('workOrder.show', ['id' => $workOrder->_id]) : null,
                    ];
                })->toArray(),
                'work_order' => $workOrderLink,
            ];

            // Добавляем объект к массиву с отформатированными данными
            $formattedObjects[] = $formattedObject;

        }
        // Возвращаем все данные в формате JSON с правильным заголовком Content-Type
        return response()->json($formattedObjects);
    }


    // ------------------ КОПИРОВАНИЕ КАРТЧОКИ ОБЪЕКТА------------------
// ------------------ КОПИРОВАНИЕ КАРТЧОКИ ОБЪЕКТА------------------
    public function copyObject(Request $request)
    {
        $id = $request->id; // Получаем идентификатор карточки объекта, которую нужно скопировать
        $originalObject = CardObjectMain::with(['services', 'documents', 'services.services_types'])->find($id);

        // Создаем копию карточки объекта
        $copiedObject = $originalObject->replicate();
        $copiedObject->name = $originalObject->name . ' копия'; // Добавляем слово "копия" в конец имени
        $copiedObject->save();

        // Создаем копии связанных сервисов карточки объекта
        foreach ($originalObject->services as $service) {
            $copiedService = $service->replicate();
            $copiedService->card_object_main_id = $copiedObject->id;
            $copiedService->save();

            // Копируем связанные данные из таблицы card_object_service_types
            foreach ($service->services_types as $serviceType) {
                $copiedServiceType = $serviceType->replicate();
                $copiedServiceType->card_id = $copiedObject->id;
                $copiedServiceType->card_services_id = $copiedService->id;
                $copiedServiceType->save();
            }
        }

        // Создаем копии связанных документов карточки объекта
        foreach ($originalObject->documents as $document) {
            $copiedDocument = $document->replicate();
            $copiedDocument->card_object_main_id = $copiedObject->id;
            $copiedDocument->save();
        }

        // Возвращаем URL новой карточки объекта в формате JSON
        return response()->json(['url' => route('cardObject', ['id' => $copiedObject->id])], 200);
    }


    // ------------------ УДАЛЕНИЕ КАРТОЧКИ ОБЪЕКТА ------------------
    public function deleteObject(Request $request)
    {
        $ids = $request->ids;

        // Удалить записи из базы данных и всех связанных данных
        foreach ($ids as $id) {
            // Удалить записи из связанных таблиц
            CardObjectMainDoc::where('card_object_main_id', $id)->delete();
            CardObjectServices::where('card_object_main_id', $id)->delete();
            CardObjectServicesTypes::where('card_id', $id)->delete();

            // Удалить запись из основной таблицы
            CardObjectMain::find($id)->delete();
        }

        return response()->json(['success' => 'Выбранные записи успешно удалены'], 200);
    }

    // ------------------ ОТОБРАЖЕНИЕ ПРОФИЛЯ ПОЛЬЗОВАТЕЛЧ------------------
    public function profile()
    {
        // Генерация хлебных крошек
        $breadcrumbs = Breadcrumbs::generate('profile');

        // Возвращение представления с передачей хлебных крошек
        return view('profile', compact('breadcrumbs'));
    }

    // ------------------ ОБНОВЛЕНИЕ ДАННЫХ ПРОФИЛЯ ------------------
    public function updateProfile(Request $request)
    {
        // Проверка входящих данных
//        $request->validate([
//            'name' => 'required|string|max:255',
//            'email' => 'required|string|email|max:255|unique:users,email,'.auth()->user()->id,
//        ]);

        // Обновление данных пользователя в MongoDB
        $user = Auth::user();
        DB::collection('users')->where('_id', $user->_id)->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        return redirect()->back()->with('success', 'Данные профиля успешно обновлены.');
    }

    // ------------------ СМЕНА ПАРОЛЯ------------------
    public function changePassword(Request $request)
    {
        // Проверка входящих данных
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Обновление пароля пользователя в MongoDB
        $user = Auth::user();
        DB::collection('users')->where('_id', $user->_id)->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->back()->with('success', 'Пароль успешно изменен.');
    }

    // ------------------ ОТОБРАЖЕНИЕ РЕЕСТРА ЗАКАЗ-НАРЯДОВ------------------
    public function reestrWorkOrdersView()
    {
        // Генерация хлебных крошек
        $breadcrumbs = Breadcrumbs::generate('reestr-work-orders');

        // Возвращение представления с передачей хлебных крошек
        return view('reestrs/reestr-work-orders', compact('breadcrumbs'));
    }



    // ------------------ ОТОБРАЖЕНИЕ УВЕДОМЛЕНИЙ (ДЕМО НЕ РАБОТАЕТ) ------------------
    public function showNotifications()
    {
        $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->get();

        return view('profile.notifications', compact('notifications'));
    }
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['read' => true]);

        return redirect()->back();
    }
}
