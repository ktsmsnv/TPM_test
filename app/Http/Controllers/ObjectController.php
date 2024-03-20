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

    // СОЗДАНИЕ карточки объекта
    public function create() {
        $breadcrumbs = Breadcrumbs::generate('card-object-create');
        return view('card-object-create', compact('breadcrumbs'));
    }

    // РЕДАКТИРОВАНИЕ карточки объекта
    public function edit() {
        $breadcrumbs = Breadcrumbs::generate('/card-object/edit');
        return view('card-object-edit', compact('breadcrumbs'));
    }
}
