<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

//контроллер для отображения данных на страницы
class workOrderController extends Controller
{

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('card-work-order');
        return view(' cards/card-workOrder', compact('breadcrumbs'));
    }
}
