<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

//контроллер для отображения данных на страницы
class GraphController extends Controller
{

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('card-graph');
        return view('card-graph', compact('breadcrumbs'));
    }
}
