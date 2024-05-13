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
     */
    public function downloadWordDocument($id)
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

        // Форматируем дату в нужный формат (день-месяц-год)
        $plannedMaintenanceDate = Carbon::parse($cardObjectServices->planned_maintenance_date)->format('d-m-Y');

        // Получаем данные для вставки в шаблон
        $data = [
            'name' => $cardObjectMain->name,
            'infrastructure' => $cardObjectMain->infrastructure,
            'performer' => $cardObjectServices->performer,
            'responsible' => $cardObjectServices->responsible,
            'location' => $cardObjectMain->location,
            'number' => $cardObjectMain->number,
            'planned_maintenance_date' => $plannedMaintenanceDate, // Используем отформатированную дату
            'consumable_materials' => $cardObjectServices->consumable_materials,
//            'type_works' => $serviceTypes->pluck('type_work')->toArray(),
        ];
        // Путь к вашему шаблону Word
        $templatePath = storage_path('app/templates/workOrderTemplate.docx');

        // Загружаем шаблон Word
        $templateProcessor = new TemplateProcessor($templatePath);

        $types = $serviceTypes->toArray(); // Преобразуем коллекцию в массив
        $templateProcessor->cloneRow('type_work', count($types));
// Обход каждой строки данных и добавление значений в соответствующие ячейки
        foreach ($types as $index => $type) {
            $templateProcessor->setValue('type_work#' . ($index + 1), $type['type_work']); // Обращаемся к элементам массива, а не к свойствам объектов
        }


        // Заменяем остальные заполнители в шаблоне данными
        foreach ($data as $key => $value) {
            // Преобразуем массив строк в одну строку
            if (is_array($value)) {
                $value = implode("\n", $value);
            }
            // Преобразуем $value в строку перед вставкой в шаблон
            $templateProcessor->setValue($key, (string) $value);
        }

        // Формируем имя файла (используем id в качестве части имени файла)
        $fileName = 'Карточка_заказ-наряда_№' . $workOrder->id . '_объекта_' . $cardObjectMain->name . '.docx';

        // Путь к новому документу Word
        $docxFilePath = storage_path('app/generated/' . $fileName);

        // Сохраняем изменения в новом документе Word
        $templateProcessor->saveAs($docxFilePath);

        // Возвращаем путь к файлу
        return $docxFilePath;
    }



//public function convertDocxToPdf($docxFilePath)
//{
//    // Загружаем содержимое из DOCX файла и преобразуем его в HTML
//    $phpWord = IOFactory::load($docxFilePath);
//    $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
//    $html = $htmlWriter->getContent();
//
//    // Преобразуем кодировку текста в UTF-8
//    $html = mb_convert_encoding($html, 'UTF-8', 'AUTO');
//   // dd($html);
//
//    $options = new Options();
//    $options->set('isHtml5ParserEnabled', true);
//    $options->set('isPhpEnabled', true);
//
//    $dompdf = new Dompdf($options);
//
//    // Загружаем HTML содержимое в Dompdf
//    $dompdf->loadHtml($html, 'UTF-8');
//
//    // Устанавливаем размеры страницы и другие параметры, если необходимо
//    $dompdf->setPaper('A4', 'portrait');
//
//    // Рендерим содержимое и сохраняем в PDF файл
//    $dompdf->render();
//    $pdfFilePath = storage_path('app/generated/' . basename($docxFilePath, '.docx') . '.pdf');
//    file_put_contents($pdfFilePath, $dompdf->output());
//
//    return $pdfFilePath;
//}

    /**
     * @throws MpdfException
     * @throws Exception
     */
    public function convertDocxToPdf($docxFilePath)
    {
        // Load the contents of the DOCX file and convert it to HTML
        $phpWord = IOFactory::load($docxFilePath);
        $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
        $html = $htmlWriter->getContent();

        // Convert the text encoding to UTF-8
        $html = mb_convert_encoding($html, 'UTF-8', 'AUTO');
        //dd($html);

        // Initialize mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
        ]);

        // Load HTML content into mPDF
        $mpdf->WriteHTML($html);
        // Save the PDF to a file
        $pdfFilePath = storage_path('app/generated/' . basename($docxFilePath, '.docx') . '.pdf');
        $mpdf->Output($pdfFilePath, 'F');

        return $pdfFilePath;
    }



    public function downloadPdfDocument($id)
    {
        // Создаем и загружаем документ Word
        $docxFilePath = $this->downloadWordDocument($id);

        // Конвертируем DOCX в PDF
        $pdfFilePath = $this->convertDocxToPdf($docxFilePath);

        // Возвращаем файл как ответ на запрос
        return response()->file($pdfFilePath);
    }

//    /**
//     * @throws CopyFileException
//     * @throws CreateTemporaryFileException
//     */
//    public function downloadPDF_create($id)
//    {
//        // Находим заказ-наряд по его ID
//        $workOrder = CardWorkOrder::findOrFail($id);
//
//        // Получаем данные о связанных записях с предварительной загрузкой связанных услуг и их типов работ
//        $cardObjectMain = CardObjectMain::with(['services' => function ($query) use ($workOrder) {
//            $query->with(['services_types'])->where('_id', $workOrder->card_object_services_id);
//        }])->find($workOrder->card_id);
//
//        // Получаем все данные о связанных услугах из card_object_services
//        $cardObjectServices = $cardObjectMain->services->first();
//
//        // Извлекаем типы работ из первой услуги
//        $serviceTypes = $cardObjectServices->services_types;
//
//        // Получаем данные для вставки в шаблон
//        $data = [
//            'infrastructure' => $cardObjectMain->infrastructure,
//            'performer' => $cardObjectServices->performer,
//            'responsible' => $cardObjectServices->responsible,
//            'location' => $cardObjectMain->location,
//            'number' => $cardObjectMain->number,
//            'planned_maintenance_date' => $cardObjectServices->planned_maintenance_date,
//            'consumable_materials' => $cardObjectServices->consumable_materials,
//            'type_works' => $serviceTypes->pluck('type_work')->toArray(),
//        ];
//
//        // Путь к вашему шаблону Word
//        $templatePath = storage_path('app/templates/workOrderTemplate.docx');
//
//        // Загружаем шаблон Word
//        $templateProcessor = new TemplateProcessor($templatePath);
//
//        // Заменяем заполнители в шаблоне данными
//        foreach ($data as $key => $value) {
//            // Преобразовываем массив строк в одну строку
//            if (is_array($value)) {
//                $value = implode("\n", $value); // или join("\n", $value)
//            }
//            // Преобразуем $value в строку перед вставкой в шаблон
//            $templateProcessor->setValue($key, (string) $value);
//        }
//
//        // Путь к новому документу Word
//        $docxFilePath = storage_path('app/generated/workOrderProcessed.docx');
//
//        // Сохраняем изменения в новом документе Word
//        $templateProcessor->saveAs($docxFilePath);
//
//        return $docxFilePath;
//    }
//
//    public function convertToPDF($docxFilePath)
//    {
//        // Создаем экземпляр PhpWord и загружаем документ Word
//        $phpWord = IOFactory::load($docxFilePath);
//
//        // Создаем экземпляр Dompdf
//        $options = new Options();
//        $options->set('isHtml5ParserEnabled', true);
//        $dompdf = new Dompdf($options);
//
//        // Получаем содержимое документа Word
//        $docxContent = file_get_contents($docxFilePath);
//
//        // Определяем кодировку текста (в данном примере используется UTF-8)
//        $encoding = 'UTF-8';
//
//        // Загружаем содержимое документа Word в Dompdf с указанием кодировки
//        $dompdf->loadHtml(mb_convert_encoding($docxContent, 'HTML-ENTITIES', $encoding), $encoding);
//
//        // Рендерим PDF (по умолчанию настраивается формат A4)
//        $dompdf->render();
//
//        // Путь к PDF-файлу
//        $pdfFilePath = public_path('generated/workOrderConverted.pdf');
//
//        // Сохраняем PDF
//        file_put_contents($pdfFilePath, $dompdf->output());
//
//        return $pdfFilePath;
//    }
//
//
//
//    public function downloadPDF($id)
//    {
//        // Создаем и загружаем PDF из документа Word
//        $docxFilePath = $this->downloadPDF_create($id);
//        $pdfFilePath = $this->convertToPDF($docxFilePath);
//
//        // Возвращаем PDF-файл как ответ на запрос
//        return response()->file($pdfFilePath);
//    }



}
