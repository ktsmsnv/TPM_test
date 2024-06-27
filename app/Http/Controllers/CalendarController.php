<?php

namespace App\Http\Controllers;

use App\Models\CardObjectMain;
use App\Models\HistoryCardCalendar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use App\Models\CardCalendar;
use Illuminate\Support\Facades\Auth;

use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Support\Facades\View;
use PhpOffice\PhpWord\IOFactory;
use Dompdf\Dompdf;

//контроллер для отображения данных на страницы
class CalendarController extends Controller
{

    public function reestrCalendarView()
    {
        // Получаем текущего пользователя
        $currentUser = Auth::user();

        // Проверяем, авторизован ли пользователь
        if ($currentUser) {
            // Получаем роль текущего пользователя
            $userRole = $currentUser->role;

            // Получаем календари с их объектами и сервисами
            $calendars = CardCalendar::with('objects.services')->get();

            // Создаем массив для хранения всех данных
            $formattedCalendars = [];

            foreach ($calendars as $cardCalendar) {
                // Проходим по каждому объекту в коллекции объектов
                foreach ($cardCalendar->objects as $object) {
                    // Инициализируем флаг для добавления объекта
                    $shouldAddObject = false;

                    // Проверяем роль текущего пользователя и фильтруем записи соответствующим образом
                    if ($userRole == 'executor') {
                        // Проверяем, если текущий пользователь исполнитель
                        foreach ($object->services as $service) {
                            if ($service->performer == $currentUser->name) {
                                $shouldAddObject = true;
                                break;
                            }
                        }
                    } elseif ($userRole == 'responsible') {
                        // Проверяем, если текущий пользователь ответственный
                        foreach ($object->services as $service) {
                            if ($service->responsible == $currentUser->name) {
                                $shouldAddObject = true;
                                break;
                            }
                        }
                    } elseif ($userRole == 'curator' || $userRole == 'admin') {
                        // Если текущий пользователь куратор или администратор, добавляем все объекты
                        $shouldAddObject = true;
                    }

                    // Если объект должен быть добавлен, формируем данные для одного объекта инфраструктуры и его сервисов
                    if ($shouldAddObject) {
                        $shortNames = $object->services->pluck('short_name')->toArray();
                        $formattedCalendar = [
                            'id' => $cardCalendar->id,
                            'infrastructure' => $object->infrastructure,
                            'name' => $object->name,
                            'number' => $object->number,
                            'location' => $object->location,
                            'short_name' => implode(', ', $shortNames),
                            'year' => $cardCalendar->year,
                            'date_create' => $cardCalendar->date_create,
                            'date_archive' => $cardCalendar->date_archive,
                            'curator' => $object->curator,
                        ];

                        // Добавляем данные объекта к массиву с отформатированными данными
                        $formattedCalendars[] = $formattedCalendar;
                    }
                }
            }

            return response()->json($formattedCalendars);
        }

        // Если пользователь не авторизован, возвращаем пустой ответ или сообщение об ошибке
        return response()->json([], 403);
    }


    public function create($id)
    {
        // Находим выбранный CardObjectMain
        $cardObjectMain = CardObjectMain::with('services')->find($id);

        $calendarEntries = CardCalendar::where('card_id', $id)->get();
        $isInCalendar = $calendarEntries->isNotEmpty();

        // Передаем выбранный объект и информацию о его наличии в календаре в представление
        return view('cards/card-calendar-create', compact('cardObjectMain', 'isInCalendar'));
    }




    public function store(Request $request)
    {

        // Создание новой записи карточки календаря
        $calendar = new cardCalendar();
        $calendar->card_id = $request->input('card_id');
        $calendar->date_create = $request->input('date_create');
        $calendar->date_archive = $request->input('date_archive');
        $calendar->year = $request->input('year');
        $calendar->save();

        // Получение ID созданной записи
        $createdId = $calendar->id;

        $history_card = new HistoryCardCalendar();
        $history_card->card_id = $request->input('card_id');
        $history_card->date_create = $request->input('date_create');
        $history_card->date_archive = $request->input('date_archive');
        $history_card->year = $request->input('year');
        $history_card->card_calendar_id = $createdId;
        $history_card->save();

        // Возвращение ответа с ID созданной записи
        return response()->json(['success' => true, 'id' => $createdId]);
    }

    public function index($id)
    {
        // Находим карточку календаря по переданному ID с загрузкой связанных объектов и услуг
        $cardCalendar = CardCalendar::with('objects.services')->find($id);

        // Проверяем, найдена ли карточка
        if (!$cardCalendar) {
            return response()->json(['error' => 'Карточка календаря не найдена'], 404);
        }

        // Находим карточку объекта, связанную с карточкой календаря
        $cardObjectMain = CardObjectMain::find($cardCalendar->card_id);

        // Проверяем, найдена ли карточка объекта
        if (!$cardObjectMain) {
            return response()->json(['error' => 'Карточка объекта не найдена'], 404);
        }

        // Собираем все услуги для календаря
        $services = collect();
        foreach ($cardCalendar->objects as $object) {
            foreach ($object->services as $service) {
                $allMaintenanceDates = $this->calculateMaintenanceDates($service);
                foreach ($allMaintenanceDates as $date) {
                    $services->push([
                        'planned_maintenance_date' => $date,
                        'short_name' => $service->short_name,
                        'calendar_color' => $service->calendar_color,
                    ]);
                }
            }
        }

        // Фильтруем коллекцию услуг по уникальным short_name
        $uniqueServices = $services->unique('short_name');

        // Передаем найденные данные в представление
        return view('cards.card-calendar', compact('cardCalendar', 'cardObjectMain', 'uniqueServices', 'services'));
    }

    private function calculateMaintenanceDates($service)
    {
        $plannedDate = Carbon::parse($service->planned_maintenance_date);
        $frequency = $service->frequency;
        $initialDay = $plannedDate->day;
        $dayOfWeek = $plannedDate->dayOfWeek;

        $maintenanceDates = [$plannedDate->format('Y-m-d')];
        $yearEnd = Carbon::now()->endOfYear();

        while ($plannedDate->lessThanOrEqualTo($yearEnd)) {
            $nextDate = $this->calculateNextDate($plannedDate, $frequency, $initialDay, $dayOfWeek);

            if ($nextDate->greaterThan($yearEnd)) {
                break;
            }

            $maintenanceDates[] = $nextDate->format('Y-m-d');
            $plannedDate = $nextDate;
        }

        return $maintenanceDates;
    }

    private function calculateNextDate($baseDate, $frequency, $initialDay, $initialDayOfWeek)
    {
        switch ($frequency) {
            case 'Ежемесячное':
                $nextDate = $baseDate->copy()->addMonth()->day($initialDay);
                break;
            case 'Ежеквартальное':
                $nextDate = $baseDate->copy()->addMonths(3)->day($initialDay);
                break;
            case 'Полугодовое':
                $nextDate = $baseDate->copy()->addMonths(6)->day($initialDay);
                break;
            case 'Ежегодное':
                $nextDate = $baseDate->copy()->addYear()->day($initialDay);
                break;
            case 'Сменное':
                $nextDate = $baseDate->copy()->addDay();
                while ($this->isWeekend($nextDate)) {
                    $nextDate->addDay();
                }
                return $nextDate;
            default:
                throw new \Exception('Unknown frequency type');
        }

        return $this->findClosestDayOfWeek($nextDate, $initialDayOfWeek);
    }

    private function findClosestDayOfWeek($baseDate, $targetDayOfWeek)
    {
        $prevDate = $baseDate->copy();
        $nextDate = $baseDate->copy();

        // Ищем ближайшие даты до и после базовой даты
        while ($prevDate->dayOfWeek !== $targetDayOfWeek) {
            $prevDate->subDay();
        }
        while ($nextDate->dayOfWeek !== $targetDayOfWeek) {
            $nextDate->addDay();
        }

        // Возвращаем дату, которая ближе к базовой дате
        if ($baseDate->diffInDays($prevDate) <= $baseDate->diffInDays($nextDate)) {
            return $prevDate;
        } else {
            return $nextDate;
        }
    }

    private function isWeekend($date)
    {
        return $date->dayOfWeek === Carbon::SATURDAY || $date->dayOfWeek === Carbon::SUNDAY;
    }

//    private function calculateMaintenanceDates($service)
//    {
//        $plannedDate = Carbon::parse($service->planned_maintenance_date);
//        $frequency = $service->frequency;
//        $dayOfWeek = $plannedDate->dayOfWeek;
//
//        $maintenanceDates = [$plannedDate->format('Y-m-d')];
//        $yearEnd = Carbon::now()->endOfYear();
//
//        while ($plannedDate->lessThanOrEqualTo($yearEnd)) {
//            $nextDate = $this->calculateNextDate($plannedDate, $frequency);
//            $closestDate = $this->findClosestDayOfWeek($nextDate, $dayOfWeek);
//
//            if ($closestDate->greaterThan($yearEnd)) {
//                break;
//            }
//
//            $maintenanceDates[] = $closestDate->format('Y-m-d');
//            $plannedDate = $closestDate;
//        }
//
//        return $maintenanceDates;
//    }
//
//    private function calculateNextDate($baseDate, $frequency)
//    {
//        switch ($frequency) {
//            case 'Ежемесячное':
//                return $baseDate->copy()->addMonth();
//            case 'Ежеквартальное':
//                return $baseDate->copy()->addMonths(3);
//            case 'Полугодовое':
//                return $baseDate->copy()->addMonths(6);
//            case 'Ежегодное':
//                return $baseDate->copy()->addYear();
//            default:
//                throw new \Exception('Unknown frequency type');
//        }
//    }
//
//    private function findClosestDayOfWeek($baseDate, $targetDayOfWeek)
//    {
//        $prevDate = $baseDate->copy();
//        $nextDate = $baseDate->copy();
//
//        // Ищем ближайшие даты до и после базовой даты
//        while ($prevDate->dayOfWeek !== $targetDayOfWeek) {
//            $prevDate->subDay();
//        }
//        while ($nextDate->dayOfWeek !== $targetDayOfWeek) {
//            $nextDate->addDay();
//        }
//
//        // Возвращаем дату, которая ближе к базовой дате
//        if ($baseDate->diffInDays($prevDate) <= $baseDate->diffInDays($nextDate)) {
//            return $prevDate;
//        } else {
//            return $nextDate;
//        }
//    }

    public function archiveCalendarDateButt(Request $request)
    {

        $dateArchive = Carbon::now()->format('Y-m-d');
        $calendarId = $request->id;
        $calendar = cardCalendar::find($calendarId);
        // Проверяем, найдена ли карточка
        if (!$calendar) {
            // Если карточка не найдена, возвращаем ошибку или редирект на страницу ошибки
            return response()->json(['error' => 'Карточка календаря не найдена'], 404);
        }
        $calendar->date_archive = $dateArchive;
        $calendar->save();

        $CardCalendar_history = new HistoryCardCalendar();
        $CardCalendar_history->card_id = $calendar->card_id;
        $CardCalendar_history->card_calendar_id =  $request->id; // Связываем заказ-наряд с выбранной карточкой объекта
        $CardCalendar_history->date_create =  $calendar->date_create;
        $CardCalendar_history->date_archive =  $calendar->date_archive; // Устанавливаем статус
        $CardCalendar_history->year =  $calendar->year;
        $CardCalendar_history->save();

        return response()->json(['message' => 'Карточка календаря успешно заархивирована'], 200);
    }

    public function view()
    {
        // Возвращение представления с передачей хлебных крошек
        return view('reestrs/reestrCalendar');
    }

    // --------------- удаление карточки заказ-наряда ---------------
    public function deleteCardCalendar(Request $request)
    {
        $ids = $request->ids;
        // Обновляем записи, устанавливая значение deleted в 1
        foreach ($ids as $id) {
            // Удалить записи из связанных таблиц
            CardCalendar::find($id)->delete();
        }

        return response()->json(['success' => true], 200);
    }

    // ------------------  РЕДАКТИРОВАНИЕ карточки графика TPM (переход на страницу) ------------------
    public function edit($id)
    {
        $cardCalendar = CardCalendar::find($id);

        // Проверяем, найдена ли карточка
        if (!$cardCalendar) {
            // Если карточка не найдена, возвращаем ошибку или редирект
            return response()->json(['error' => 'Карточка календаря не найдена'], 404);
        }

        // Находим связанную с карточкой календаря карточку объекта
        $cardObjectMain = CardObjectMain::find($cardCalendar->card_id);

        // Проверяем, найдена ли карточка объекта
        if (!$cardObjectMain) {
            // Если карточка объекта не найдена, возвращаем ошибку или редирект
            return response()->json(['error' => 'Карточка объекта не найдена'], 404);
        }

        // Определяем массив месяцев
        $months = ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];

        // Собираем все услуги для календаря
        $services = collect();
        foreach ($cardCalendar->objects as $object) {
            foreach ($object->services as $service) {
                $allMaintenanceDates = $this->calculateMaintenanceDates($service);
                foreach ($allMaintenanceDates as $date) {
                    $services->push([
                        'planned_maintenance_date' => $date,
                        'short_name' => $service->short_name,
                        'calendar_color' => $service->calendar_color,
                    ]);
                }
            }
        }

        // Фильтруем коллекцию услуг по уникальным short_name
        $uniqueServices = $services->unique('short_name');

        // Передаем данные в представление
        return view('cards/card-calendar-edit', compact('cardCalendar',
            'cardObjectMain', 'uniqueServices', 'services', 'months'));
    }

    public function editSave(Request $request, $id)
    {
        // Находим карточку календаря по переданному идентификатору
        $cardCalendar = CardCalendar::find($id);
        // Проверяем, найдена ли карточка
        if (!$cardCalendar) {
            // Если карточка не найдена, возвращаем ошибку или редирект на страницу ошибки
            return response()->json(['error' => 'Карточка календаря не найдена'], 404);
        }

        // Обновляем основные данные карточки календаря
        $cardCalendar->date_create = $request->date_create;
        $cardCalendar->date_archive = $request->date_archive;
        $cardCalendar->year = $request->year;


        // Сохраняем изменения
        $cardCalendar->save();

//        $history_card = new HistoryCardCalendar();
//        $history_card->name =  $cardCalendar->name;
//        $history_card->infrastructure_type = $cardCalendar->infrastructure_type;
//        $history_card->curator = $request->curator;
//        $history_card->year_action = $request->year_action;
//        $history_card->date_create = $request->date_create;
//        $history_card->date_last_save = $request->date_last_save;
//        $history_card->date_archive = $request->date_archive;
//        $history_card->cards_ids =  $cardCalendar->cards_ids;
//        $history_card->card_graph_id = $cardCalendar->card_graph_id;
//        $history_card->save();


        // Возвращаем успешный ответ или редирект на страницу карточки объекта
        return response()->json(['success' => 'Данные карточки календаря успешно обновлены'], 200);
    }


// -------------- выгрузка графика в WORD ---------------
    public function downloadCalendar($id)
    {
        // Создаем Word документ
        $docxFilePath = $this->downloadCalendar_create($id);

        // Получаем данные для имени файла
        $data_CardCalendar = CardCalendar::with('objects.services')->find($id);
        $cardObjectMain = CardObjectMain::find($data_CardCalendar->card_id);

        $name = $cardObjectMain->name;
        // Определяем имя файла для скачивания
        $fileName = 'Карточка_календаря_' . $name . '.docx';

        // Возвращаем Word-файл как ответ на запрос с заголовком для скачивания
        return response()->download($docxFilePath, $fileName);
    }

// -------------- выгрузка графика в WORD ---------------
    public function downloadCalendar_create($id)
    {
        // Находим заказ-наряд по его ID
        $cardCalendar = CardCalendar::with('objects.services.services_types')->find($id);
        if (!$cardCalendar) {
            abort(404, 'CardCalendar not found');
        }

        // Получаем данные о связанных записях с предварительной загрузкой связанных услуг и их типов работ
        $cardObjectMain = CardObjectMain::with(['services.services_types'])->find($cardCalendar->card_id);
        if (!$cardObjectMain) {
            abort(404, 'CardObjectMain not found');
        }

        // Получаем данные для вставки в шаблон
        $data = [
            'name' => $cardObjectMain->name,
            'infrastructure' => $cardObjectMain->infrastructure,
            'location' => $cardObjectMain->location,
            'number' => $cardObjectMain->number,
            'year' => $cardCalendar->year,
        ];

        // Собираем материалы всех услуг в одну строку, разделяя их запятой
        $materials = [];
        foreach ($cardObjectMain->services as $service) {
            $materials[] = $service->consumable_materials;
        }
        $materialsString = implode(', ', $materials);
        $data['materials'] = $materialsString;

        $templatePath = storage_path('app/templates/calendar_template.docx');
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // Добавляем изображение
        if ($cardObjectMain->image) {
            // Получаем бинарные данные изображения
            $imageData = $cardObjectMain->image->getData();

            // Создаем временный файл для изображения
            $tempImagePath = storage_path('app/public/images/temp_image_' . $cardObjectMain->id . '.png');
            file_put_contents($tempImagePath, $imageData);

            // Проверяем, существует ли файл изображения
            if (file_exists($tempImagePath)) {
                $templateProcessor->setImageValue('image', [
                    'path' => $tempImagePath,
                    'width' => 100, // Установите нужные размеры
                    'height' => 100,
                ]);
            } else {
                // Выводим сообщение об ошибке, если файл не найден
                abort(404, 'Image file not found');
            }
        }

        // Конструируем массив замен для клонирования блока обслуживания
        $serviceReplacements = [];
        foreach ($cardObjectMain->services as $serviceIndex => $service) {
            $serviceBlock = [
                'service_type' => $service->service_type,
                'frequency' => $service->frequency,
                'performer' => $service->performer,
                'responsible' => $service->responsible,
            ];

            // Конструируем массив замен для вложенного блока типов работ
            $typeWorkReplacements = [];
            foreach ($service->services_types as $typeWork) {
                $typeWorkReplacements[] = [
                    'type_work' => $typeWork->type_work,
                ];
            }

            // Добавляем данные для клонирования вложенного блока типов работ
            $serviceBlock['type_work_block'] = $typeWorkReplacements;

            // Добавляем данные для клонирования блока обслуживания
            $serviceReplacements[] = $serviceBlock;
        }

        // Клонируем блок обслуживания
        $templateProcessor->cloneBlock('service_block', count($serviceReplacements), true, false);
        foreach ($serviceReplacements as $index => $serviceReplacement) {
            $templateProcessor->setValue('service_type#' . ($index + 1), $serviceReplacement['service_type']);
            $templateProcessor->setValue('frequency#' . ($index + 1), $serviceReplacement['frequency']);
            $templateProcessor->setValue('performer#' . ($index + 1), $serviceReplacement['performer']);
            $templateProcessor->setValue('responsible#' . ($index + 1), $serviceReplacement['responsible']);

            // Клонируем и заполняем вложенные блоки типов работ
            $typeWorkReplacements = $serviceReplacement['type_work_block'];
            $templateProcessor->cloneBlock('type_work_block#' . ($index + 1), count($typeWorkReplacements), true, true);
            foreach ($typeWorkReplacements as $typeWorkIndex => $typeWorkReplacement) {
                $templateProcessor->setValue('type_work#' . ($index + 1) . '#' . ($typeWorkIndex + 1), $typeWorkReplacement['type_work']);
            }
        }

        // Устанавливаем остальные значения в шаблоне
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = implode("\n", $value);
            }
            $templateProcessor->setValue($key, (string)$value);
        }

        $docxFilePath = storage_path('app/generated/calendarProcessed.docx');
        $templateProcessor->saveAs($docxFilePath);

        return $docxFilePath;
    }


}
