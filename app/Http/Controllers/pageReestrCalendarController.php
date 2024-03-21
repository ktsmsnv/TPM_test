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

    public function index()
    {
        $pageReestrCalendar = pageReestrCalendar::all();

        return view('reestrCalendar', compact('pageReestrCalendar'));
    }

    public function reestrCalendarView()
    {
        // Генерация хлебных крошек
        $breadcrumbs = Breadcrumbs::generate('pageReestrCalendar');

        // Возвращение представления с передачей хлебных крошек
        return view('reestrCalendar', compact('breadcrumbs'));
    }
}
