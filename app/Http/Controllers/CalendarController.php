<?php

namespace App\Http\Controllers;

use App\Models\CardObjectMain;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use App\Models\CardCalendar;

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
        // Логика сохранения карточки календаря в базу данных
    }
}
