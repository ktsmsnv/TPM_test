<?php

namespace App\Http\Controllers;

use App\Models\CardObjectMain;
use App\Models\CardObjectServices;
use App\Models\CardWorkOrder;
use App\Models\HistoryCardWorkOrder;
use App\Models\User;
use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
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
    public function getCurrentUserRole($role)
    {
        // Получаем пользователей с указанной ролью
        $users = User::where('role', $role)->get();

        return $users;
    }
    public function index() {
        // Получаем текущего пользователя
        $currentUser = Auth::user();

        // Проверяем, аутентифицирован ли пользователь
        if ($currentUser) {
            // Получаем роль текущего пользователя
            $userRole = $currentUser->role;

            // Получаем все заказы-наряды с отношениями к объектам обслуживания и главным объектам
            $workOrders = CardWorkOrder::with('cardObjectServices.cardObjectMain')->get();
            // Создаем массив для хранения всех данных
            $formattedWorkOrders = [];

            // Проходимся по каждому заказу-наряду и выбираем все поля
            foreach ($workOrders as $workOrder) {
                // Проверяем роль текущего пользователя и фильтруем записи соответствующим образом
                if (($userRole == 'executor' && $workOrder->cardObjectServices->performer == $currentUser->name) ||
                    ($userRole == 'responsible' && $workOrder->cardObjectServices->responsible == $currentUser->name) ||
                    ($userRole == 'curator' || $userRole == 'admin')) {
                    $formattedWorkOrder = [
                        'id' => $workOrder->id,
                        'infrastructure' => $workOrder->cardObjectServices->cardObjectMain->infrastructure ?? null,
                        'name' => $workOrder->cardObjectServices->cardObjectMain->name,
                        'number' => $workOrder->cardObjectServices->cardObjectMain->number,
                        'location' => $workOrder->cardObjectServices->cardObjectMain->location,
                        'service_type' => $workOrder->cardObjectServices->service_type,
                        'planned_maintenance_date' => $workOrder->cardObjectServices->planned_maintenance_date,
                        'prev_maintenance_date' => $workOrder->cardObjectServices->prev_maintenance_date,
                        'status' => $workOrder->status,
                        'date_create' => $workOrder->date_create,
                        'performer' => $workOrder->cardObjectServices->performer,
                        'responsible' => $workOrder->cardObjectServices->responsible,
                    ];

                    // Добавляем заказ-наряд к массиву с отформатированными данными
                    $formattedWorkOrders[] = $formattedWorkOrder;
                }
            }

            // Возвращаем все данные в формате JSON с правильным заголовком Content-Type
            return response()->json($formattedWorkOrders);
        }
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
        $selectedIds = $request->selected_ids;
        $now = Carbon::now();
        $results = [];

        foreach ($selectedIds as $selectedId) {
            $cardObjectMain = CardObjectMain::findOrFail($selectedId);

            // Проверяем, существует ли заказ-наряд для данной карточки объекта и ее обслуживания, который не имеет значения date_fact
            $existingWorkOrder = CardWorkOrder::where('card_id', $selectedId)
                ->whereIn('card_object_services_id', $cardObjectMain->services->pluck('id'))
                ->whereNull('date_fact')
                ->first();

            if ($existingWorkOrder) {
                $results[] = [
                    'id' => $existingWorkOrder->id,
                    'status' => 'error',
                    'message' => 'Заказ-наряд уже существует для объекта ID ' . $selectedId,
                    'url' => route('workOrder.show', ['id' => $existingWorkOrder->id]),
                ];
                continue;
            }

            $nearestService = $cardObjectMain->services->sortBy('planned_maintenance_date')->first();

            if ($nearestService) {
                $newWorkOrder = new CardWorkOrder();
                $newWorkOrder->card_id = $selectedId;
                $newWorkOrder->card_object_services_id = $nearestService->id;
                $newWorkOrder->date_create = $now->format('d-m-Y');
                $newWorkOrder->status = 'В работе';
                $newWorkOrder->number = CardWorkOrder::where('card_id', $selectedId)->count() + 1;
                $newWorkOrder->planned_maintenance_date = $nearestService->planned_maintenance_date;
                $newWorkOrder->save();

                $newWorkOrder_history = new HistoryCardWorkOrder();
                $newWorkOrder_history->card_id = $selectedId;
                $newWorkOrder_history->card_object_services_id = $nearestService->id;
                $newWorkOrder_history->date_create = $now->format('d-m-Y');
                $newWorkOrder_history->status = 'В работе';
                $newWorkOrder_history->number = $newWorkOrder->number;
                $newWorkOrder_history->planned_maintenance_date = $nearestService->planned_maintenance_date;
                $newWorkOrder_history->save();

                $results[] = [
                    'id' => $newWorkOrder->id,
                    'status' => 'success',
                    'message' => 'Заказ-наряд создан для объекта ID ' . $selectedId,
                    'url' => route('workOrder.show', ['id' => $newWorkOrder->id]),
                ];
            } else {
                $results[] = [
                    'id' => $selectedId,
                    'status' => 'error',
                    'message' => 'Не найдено запланированное обслуживание для объекта ID ' . $selectedId,
                ];
            }
        }

        return response()->json(['results' => $results], 200);
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
        $newWorkOrder_history->planned_maintenance_date =$workOrder->planned_maintenance_date;
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
            'planned_maintenance_date' => $workOrder->planned_maintenance_date,
            'consumable_materials' => $cardObjectServices->consumable_materials,
            'service_type' => $cardObjectServices->service_type,
            'frequency' => $cardObjectServices->frequency,
            'type_works' => $serviceTypes->pluck('type_work')->toArray(),
        ];

        // Получаем значение calendar_color
        $calendarColor = $cardObjectServices->calendar_color;
        // Определяем путь к шаблону в зависимости от calendar_color
        switch ($calendarColor) {
            case '#ff0000':
                $templatePath = storage_path('app/templates/red_workOrderTemplate.docx');
                break;
            case '#00ff00':
                $templatePath = storage_path('app/templates/green_workOrderTemplate.docx');
                break;
            case '#0000ff':
                $templatePath = storage_path('app/templates/blue_workOrderTemplate.docx');
                break;
            default:
                // Если calendar_color не соответствует ни одному известному значению, используем общий шаблон
                $templatePath = storage_path('app/templates/workOrderTemplate.docx');
                break;
        }


        // Загружаем шаблон Word
        $templateProcessor = new TemplateProcessor($templatePath);

        // Клонируем строки для каждого типа работ
        $templateProcessor->cloneRow('type_work', count($serviceTypes));

        // Обход каждого типа работ и добавление значений в соответствующие ячейки
        foreach ($serviceTypes as $index => $type) {
            $templateProcessor->setValue('type_work#' . ($index + 1), (string) $type->type_work);
        }


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

        $data_CardWork =  CardWorkOrder::findOrFail($id);
        $cardObjectMain = CardObjectMain::with(['services' => function ($query) use ($data_CardWork) {
            $query->with(['services_types'])->where('_id', $data_CardWork->card_object_services_id);
        }])->find($data_CardWork->card_id);
        $name = $cardObjectMain->name;

        // Определяем имя файла для скачивания
        $fileName = 'Карточка_заказ-наряда_' . $name . '.docx';

        // Возвращаем Word-файл как ответ на запрос с заголовком для скачивания
        return response()->download($docxFilePath, $fileName);
    }



}
