<?php

namespace App\Http\Controllers;

use App\Models\pageReestrCalendar;

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
}
