<?php

namespace App\Http\Controllers;

use App\Models\CardObjectMain;
use App\Models\CardObjectServices;
use App\Models\CardWorkOrder;
use App\Models\HistoryCardWorkOrder;
use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Mpdf\MpdfException;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Dompdf\Dompdf;
use Dompdf\Options;

use Mpdf\Mpdf;


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

                $newWorkOrder_history = new HistoryCardWorkOrder();
                $newWorkOrder_history->card_id = $selectedId; // Связываем заказ-наряд с выбранной карточкой объекта
                $newWorkOrder_history->card_object_services_id = $nearestService->id; // Связываем заказ-наряд с ближайшей услугой
                $newWorkOrder_history->date_create = $now->format('d-m-Y');
                $newWorkOrder_history->status = 'В работе'; // Устанавливаем статус
                // Присваиваем номер заказа-наряда
                $newWorkOrder_history->number = $existingOrdersCount + 1;
                $newWorkOrder_history->save();
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

        $newWorkOrder_history = new HistoryCardWorkOrder();
        $newWorkOrder_history->card_id = $workOrder->card_id; // Связываем заказ-наряд с выбранной карточкой объекта
        $newWorkOrder_history->card_object_services_id = $workOrder->card_object_services_id; // Связываем заказ-наряд с ближайшей услугой
        $newWorkOrder_history->date_create =  $workOrder->date_create;
        $newWorkOrder_history->status =  $request->status; // Устанавливаем статус
        $newWorkOrder_history->date_fact = $dateFact;
        // Присваиваем номер заказа-наряда
        $newWorkOrder_history->number = $workOrder->number;
        $newWorkOrder_history->save();


        return response()->json(['message' => 'Заказ-наряд успешно завершен'], 200);
    }


    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     * @throws Exception
     */
    public function downloadPDF_create($id)
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

        // Получаем данные для вставки в шаблон
        $data = [
            'name' => $cardObjectMain->name,
            'infrastructure' => $cardObjectMain->infrastructure,
            'performer' => $cardObjectServices->performer,
            'responsible' => $cardObjectServices->responsible,
            'location' => $cardObjectMain->location,
            'number' => $cardObjectMain->number,
            'planned_maintenance_date' => $cardObjectServices->planned_maintenance_date,
            'consumable_materials' => $cardObjectServices->consumable_materials,
            'type_works' => $serviceTypes->pluck('type_work')->toArray(),
        ];

        // Путь к вашему шаблону Word
        $templatePath = storage_path('app/templates/workOrderTemplate.docx');

        // Загружаем шаблон Word
        $templateProcessor = new TemplateProcessor($templatePath);

        // Клонируем блок с маркером "type_work" в шаблоне для каждого типа работ
        foreach ($data['type_works'] as $type_work) {
            $templateProcessor->cloneBlock('type_work', 1, true, false, ['type_work' => $type_work]);
        }


// Заменяем остальные заполнители в шаблоне данными
        unset($data['type_works']); // Удаляем типы работ из данных, так как они уже вставлены в шаблон
        foreach ($data as $key => $value) {
            // Преобразуем массив строк в одну строку
            if (is_array($value)) {
                $value = implode("\n", $value);
            }
            // Преобразуем $value в строку перед вставкой в шаблон
            $templateProcessor->setValue($key, (string) $value);
        }


        // Путь к новому документу Word
        $docxFilePath = storage_path('app/generated/workOrderProcessed.docx');

        // Сохраняем изменения в новом документе Word
        $templateProcessor->saveAs($docxFilePath);

        return $docxFilePath;
    }


    public function downloadPDF($id)
    {
// Создаем Word документ
        $docxFilePath = $this->downloadPDF_create($id);

        // Определяем имя файла для скачивания
        $fileName = 'Карточка_заказ-наряда_' . $id . '.docx';

        // Возвращаем Word-файл как ответ на запрос с заголовком для скачивания
        return response()->download($docxFilePath, $fileName);
    }



}
