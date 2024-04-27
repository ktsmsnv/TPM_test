<?php

namespace App\Http\Controllers;

use App\Models\CardObjectMain;
use App\Models\CardObjectServices;
use App\Models\CardWorkOrder;
use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Carbon\Carbon;

// --------------- контроллер для отображения данных на страницы ---------------
class workOrderController extends Controller
{

    public function index() {
        // Получаем все заказы-наряды с отношениями к объектам обслуживания и главным объектам
        $workOrders = CardWorkOrder::with('cardObjectServices.cardObjectMain')->get();

        // Создаем массив для хранения всех данных
        $formattedWorkOrders = [];

        // Проходимся по каждому заказу-наряду и выбираем все поля
        foreach ($workOrders as $workOrder) {
            $formattedWorkOrder = [
                'id' => $workOrder->id,
                'infrastructure' => $workOrder->cardObjectServices->cardObjectMain->infrastructure,
                'name' => $workOrder->cardObjectServices->cardObjectMain->name,
                'number' => $workOrder->cardObjectServices->cardObjectMain->number,
                'location' => $workOrder->cardObjectServices->cardObjectMain->location,
                'service_type' => $workOrder->cardObjectServices->service_type,
                'planned_maintenance_date' => $workOrder->cardObjectServices->planned_maintenance_date,
                'prev_maintenance_date' => $workOrder->cardObjectServices->prev_maintenance_date,
                'status' => $workOrder->status,
                'date_create' => $workOrder->date_create,
//                'date_last_save' => $workOrder->date_last_save,
                'performer' => $workOrder->cardObjectServices->performer,
                'responsible' => $workOrder->cardObjectServices->responsible,
                // Добавьте другие поля, если необходимо
            ];

            // Добавляем заказ-наряд к массиву с отформатированными данными
            $formattedWorkOrders[] = $formattedWorkOrder;
        }

        // Возвращаем все данные в формате JSON с правильным заголовком Content-Type
        return response()->json($formattedWorkOrders);
    }

// --------------- контроллер для отображения данных на страницы ---------------
    public function show($id)
    {
        // Находим заказ-наряд по его ID
        $workOrder = CardWorkOrder::findOrFail($id);

        // Получаем данные о связанных записях с предварительной загрузкой связанных услуг и их типов работ
        $cardObjectMain = CardObjectMain::with(['services' => function ($query) use ($workOrder) {
            $query->with(['services_types'])->where('_id', $workOrder->card_object_services_id);
        }])->find($workOrder->card_id);

        // Получаем все данные о связанных услугах из card_object_services
        $cardObjectServices = $cardObjectMain->services->first();

        // Извлекаем типы работ из первой услуги
        $serviceTypes = $cardObjectServices->services_types;

        // Передаем данные в шаблон и отображаем карточку заказ-наряда
        return view('cards.card-workOrder', compact('workOrder', 'cardObjectMain', 'cardObjectServices', 'serviceTypes'));
    }


// --------------- создание карточки заказ-наряда ---------------
    public function create(Request $request)
    {
        // Получаем ID выбранных записей из запроса
        $selectedIds = $request->selected_ids;
        $now = Carbon::now();
        // Создаем новый заказ-наряд для каждого выбранного ID
        foreach ($selectedIds as $selectedId) {
            // Находим карточку объекта по ID
            $cardObjectMain = CardObjectMain::findOrFail($selectedId);

            // Проверяем, существует ли заказ-наряд для данной карточки объекта и ее обслуживания
            $existingWorkOrder = CardWorkOrder::where('card_id', $selectedId)
                ->whereIn('card_object_services_id', $cardObjectMain->services->pluck('id'))
                ->exists();
            // Если заказ-наряд уже существует, отправляем уведомление
            if ($existingWorkOrder) {
                return response()->json(['message' => 'Заказ-наряд уже существует для выбранного объекта'], 400);
            }

            // Находим количество заказов-нарядов для данной карточки объекта
            $existingOrdersCount = CardWorkOrder::where('card_id', $selectedId)->count();

            // Находим ближайшее обслуживание для карточки объекта
            $nearestService = $cardObjectMain->services->sortBy('planned_maintenance_date')->first();

            // Если ближайшее обслуживание найдено, создаем новый заказ-наряд и связываем его с этим обслуживанием
            if ($nearestService) {
                $newWorkOrder = new CardWorkOrder();
                $newWorkOrder->card_id = $selectedId; // Связываем заказ-наряд с выбранной карточкой объекта
                $newWorkOrder->card_object_services_id = $nearestService->id; // Связываем заказ-наряд с ближайшей услугой
                $newWorkOrder->date_create = $now->format('d-m-Y');
                // $newWorkOrder->date_last_save = $now->format('d-m-Y');
                $newWorkOrder->status = 'В работе'; // Устанавливаем статус

                // Присваиваем номер заказа-наряда
                $newWorkOrder->number = $existingOrdersCount + 1;

                $newWorkOrder->save();
            }
        }

        // Возвращаем URL страницы нового заказ-наряда
        $url = route('workOrder.show', ['id' => $newWorkOrder->id]);

        return response()->json(['url' => $url], 200);
    }

// --------------- удаление карточки заказ-наряда ---------------
    public function deleteWorkOrder(Request $request)
    {
        $ids = $request->ids;
        // Обновляем записи, устанавливая значение deleted в 1
        foreach ($ids as $id) {
            // Удалить записи из связанных таблиц
            CardWorkOrder::find($id)->delete();
        }

        return response()->json(['success' => true], 200);
    }

// --------------- завершение  заказ-наряда ---------------
    public function endWorkOrder(Request $request)
    {
        $workOrderId = $request->id;
        $dateFact = Carbon::now()->format('d-m-Y');
        $status = $request->status;

        // Найдите заказ-наряд по его ID и обновите фактическую дату и статус
        $workOrder = CardWorkOrder::findOrFail($workOrderId);
        $workOrder->date_fact = $dateFact;
        $workOrder->status = $status;
        $workOrder->save();

        // Обновите дату предыдущего обслуживания в объекте CardObjectServices
        $cardObjectServicesId = $workOrder->card_object_services_id;
        $cardObjectServices = CardObjectServices::findOrFail($cardObjectServicesId);
        $cardObjectServices->prev_maintenance_date = $dateFact;
        $cardObjectServices->save();

        return response()->json(['message' => 'Заказ-наряд успешно завершен'], 200);
    }

}
