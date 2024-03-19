<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//контроллер для отображения данных на страницы
class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        return view('home');
    }
    public function reestrWorkOrdersView()
    {
        return view('reestr-work-orders');
    }
}
