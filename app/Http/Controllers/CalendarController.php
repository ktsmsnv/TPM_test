<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//контроллер для отображения данных на страницы
class CalendarController extends Controller
{

    public function index()
    {
        return view('card-calendar');
    }
}
