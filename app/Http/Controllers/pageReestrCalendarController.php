<?php

namespace App\Http\Controllers;

use App\Models\pageReestrCalendar;

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class pageReestrCalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reestrCalendarView()
    {
        // Генерация хлебных крошек
        $breadcrumbs = Breadcrumbs::generate('pageReestrCalendar');
        $pageReestrCalendar = pageReestrCalendar::all();

        // Возвращение представления с передачей хлебных крошек
        return view('reestrCalendar', compact('breadcrumbs', 'pageReestrCalendar'));
    }
}
