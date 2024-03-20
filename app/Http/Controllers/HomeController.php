<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Генерация хлебных крошек
        $breadcrumbs = Breadcrumbs::generate('home');

        // Возвращение представления с передачей хлебных крошек
        return view('home', compact('breadcrumbs'));
    }

    public function reestrWorkOrdersView()
    {
        // Генерация хлебных крошек
        $breadcrumbs = Breadcrumbs::generate('reestr-work-orders');

        // Возвращение представления с передачей хлебных крошек
        return view('reestr-work-orders', compact('breadcrumbs'));
    }
}
