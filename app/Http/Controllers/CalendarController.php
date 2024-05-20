<?php

namespace App\Http\Controllers;

use App\Models\CardObjectMain;
use App\Models\HistoryCardCalendar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use App\Models\CardCalendar;

//контроллер для отображения данных на страницы
class CalendarController extends Controller
{

    public function reestrCalendarView()
    {
        // Получаем объекты инфраструктуры с их сервисами
        $calendars = CardCalendar::with('objects.services')->get();
//        dd($calendars);
        // Создаем массив для хранения всех данных
        $formattedCalendars = [];

        foreach ($calendars as $cardCalendar) {
            // Проходим по каждому объекту в коллекции объектов
            foreach ($cardCalendar->objects as $object) {
                $shortNames = $object->services->pluck('short_name')->toArray();
                // Формируем данные для одного объекта инфраструктуры и его сервисов
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

        return response()->json($formattedCalendars);
    }


    public function create($id)
    {
        $cardObjectMain = CardObjectMain::findOrFail($id);
        return view('cards/card-calendar-create', compact('cardObjectMain'));
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
        $history_card->card_calendar_id =  $createdId;
        $history_card->save();

        // Возвращение ответа с ID созданной записи
        return response()->json(['success' => true, 'id' => $createdId]);
    }
    public function index($id)
    {
        // Находим карточку календаря по переданному ID
        $cardCalendar = CardCalendar::with('objects.services')->find($id);

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
        $services = [];
        foreach ($cardCalendar->objects as $object) {
            foreach ($object->services as $service) {
                $services[] = [
                    'planned_maintenance_date' => $service->planned_maintenance_date,
                    'short_name' => $service->short_name,
                ];
            }
        }

        // Передаем найденные данные в представление
        return view('cards.card-calendar', compact('cardCalendar', 'cardObjectMain', 'services', 'months'));
    }


    public function archiveCalendar(Request $request) {
        $calendarId = $request->id;
        $dateArchive = Carbon::now()->format('Y-m-d');

        $calendar = cardCalendar::findOrFail($calendarId);
        $calendar->date_archive = $dateArchive;
        $calendar->save();

        return response()->json(['message' => 'Календарь успешно заархивирован'], 200);
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
}
