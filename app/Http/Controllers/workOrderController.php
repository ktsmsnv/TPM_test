<?php

namespace App\Http\Controllers;

use App\Models\CardObjectMain;
use App\Models\CardWorkOrder;
use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Carbon\Carbon;

//контроллер для отображения данных на страницы
class workOrderController extends Controller
{

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('card-work-order');
        return view(' cards/card-workOrder', compact('breadcrumbs'));
    }

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





    public function create(Request $request)
    {
        // Получаем ID выбранных записей из запроса
        $selectedIds = $request->selected_ids;
        $now = Carbon::now();
        // Создаем новый заказ-наряд для каждого выбранного ID
        foreach ($selectedIds as $selectedId) {
            // Находим карточку объекта по ID
            $cardObjectMain = CardObjectMain::findOrFail($selectedId);

            // Находим ближайшее обслуживание для карточки объекта
            $nearestService = $cardObjectMain->services->sortBy('planned_maintenance_date')->first();

            // Если ближайшее обслуживание найдено, создаем новый заказ-наряд и связываем его с этим обслуживанием
            if ($nearestService) {
                $newWorkOrder = new CardWorkOrder();
                $newWorkOrder->card_id = $selectedId; // Связываем заказ-наряд с выбранной карточкой объекта
                $newWorkOrder->card_object_services_id = $nearestService->id; // Связываем заказ-наряд с ближайшей услугой
                $newWorkOrder->date_create = $now->format('d-m-Y');
                $newWorkOrder->date_last_save = $now->format('d-m-Y');
                $newWorkOrder->status = 'В работе'; // Устанавливаем статус
                $newWorkOrder->save();
            }
        }

        // Возвращаем URL страницы нового заказ-наряда
        $url = route('workOrder.show', ['id' => $newWorkOrder->id]);

        return response()->json(['url' => $url], 200);
    }



}
