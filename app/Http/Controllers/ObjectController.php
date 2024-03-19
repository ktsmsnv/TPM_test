<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

//контроллер для отображения данных на страницы
class ObjectController extends Controller
{

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('card-object');
        return view('card-object', compact('breadcrumbs'));
    }
}
