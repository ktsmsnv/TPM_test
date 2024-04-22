<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\CardObjectMain;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CardObjectMainDoc;
use App\Models\CardObjectServices;
use App\Models\CardObjectServicesTypes;
class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $objects = CardObjectMain::with('services')->get();
        $breadcrumbs = Breadcrumbs::generate('home');
        return view('home', compact('breadcrumbs', 'objects'));
    }
    public function getObjects() {
        // Получаем объекты инфраструктуры с их сервисами
        $objects = CardObjectMain::with('services')->get();
        // Создаем массив для хранения всех данных
        $formattedObjects = [];
        // Проходимся по каждому объекту и выбираем все поля
        foreach ($objects as $object) {
            $formattedObject = [
                'id' => $object->id,
                'infrastructure' => $object->infrastructure,
                'name' => $object->name,
                'number' => $object->number,
                'location' => $object->location,
                'date_arrival' => $object->date_arrival,
                'date_usage_end' => $object->date_usage_end,
                'date_cert_end' => $object->date_cert_end,
                'services' => $object->services->map(function($service) {
                    return [
                        'service_type' => $service->service_type,
                        'short_name' => $service->short_name,
                        'performer' => $service->performer,
                        'responsible' => $service->responsible,
                        'frequency' => $service->frequency,
                        'prev_maintenance_date' => $service->prev_maintenance_date,
                        'planned_maintenance_date' => $service->planned_maintenance_date,
                        'calendar_color' => $service->calendar_color,
                        'consumable_materials' => $service->consumable_materials,
                    ];
                })->toArray(),
            ];

            // Добавляем объект к массиву с отформатированными данными
            $formattedObjects[] = $formattedObject;
        }
        // Возвращаем все данные в формате JSON с правильным заголовком Content-Type
        return response()->json($formattedObjects);
    }

    public function copyObject(Request $request)
    {
        $id = $request->id; // Получаем идентификатор карточки объекта, которую нужно скопировать
        $originalObject = CardObjectMain::with(['services', 'documents', 'services.services_types'])->find($id);

        // Создаем копию карточки объекта
        $copiedObject = $originalObject->replicate();
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

        return response()->json(['success' => 'Карточка объекта успешно скопирована'], 200);
    }




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

    public function profile()
    {
        // Генерация хлебных крошек
        $breadcrumbs = Breadcrumbs::generate('profile');

        // Возвращение представления с передачей хлебных крошек
        return view('profile', compact('breadcrumbs'));
    }

// Метод для обновления данных пользователя
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

// Метод для смены пароля пользователя
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


    public function reestrWorkOrdersView()
    {
        // Генерация хлебных крошек
        $breadcrumbs = Breadcrumbs::generate('reestr-work-orders');

        // Возвращение представления с передачей хлебных крошек
        return view('reestrs/reestr-work-orders', compact('breadcrumbs'));
    }
}
