<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

//контроллер для отображения данных на страницы
class CalendarController extends Controller
{

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('card-calendar');
        return view('cards/card-calendar', compact('breadcrumbs'));
    }
}
