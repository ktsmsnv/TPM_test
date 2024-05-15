<?php

namespace App\Http\Controllers;

use App\Models\CardObjectMain;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use App\Models\cardcalendar;

//контроллер для отображения данных на страницы
class CalendarController extends Controller
{

    public function create($id)
    {
        $cardObjectMain = CardObjectMain::findOrFail($id);
        return view('cards/card-calendar-create', compact('cardObjectMain'));
    }


    public function store(Request $request)
    {

        // Создание новой записи карточки календаря
        $calendar = new cardcalendar();
        $calendar->card_id = $request->input('card_id');
        $calendar->date_create = $request->input('date_create');
        $calendar->date_archive = $request->input('date_archive');
        $calendar->year = $request->input('year');
        $calendar->save();

        // Получение ID созданной записи
        $createdId = $calendar->id;

        // Возвращение ответа с ID созданной записи
        return response()->json(['success' => true, 'id' => $createdId]);
    }


    public function index($id)
    {
        // Находим карточку календаря по переданному ID
        $cardCalendar = cardcalendar::find($id);

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

        // Передаем найденные данные в представление
        return view('cards/card-calendar', compact('cardCalendar', 'cardObjectMain'));
    }

    public function archiveCalendar(Request $request) {
        $calendarId = $request->id;
        $dateArchive = Carbon::now()->format('Y-m-d');

        $calendar = cardcalendar::findOrFail($calendarId);
        $calendar->date_archive = $dateArchive;
        $calendar->save();

        return response()->json(['message' => 'Календарь успешно заархивирован'], 200);
    }
}
