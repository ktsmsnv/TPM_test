<?php

namespace App\Http\Controllers;

use App\Models\pageReestrGraph;

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class pageReestrGraphController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reestrGraphView()
    {
        // Генерация хлебных крошек
        $breadcrumbs = Breadcrumbs::generate('pageReestrGraph');
        $pageReestrGraph = pageReestrGraph::all();

        // Возвращение представления с передачей хлебных крошек
        return view('reestrs/reestrGraph', compact('breadcrumbs', 'pageReestrGraph'));
    }

    public function getContractStorage($id)
    {
        $pageReestrGraph = pageReestrGraph::find($id);
        return response()->json($pageReestrGraph);
    }

    public function getReestrGraphDetails($id)
    {
        $pageReestrGraph = pageReestrGraph::findOrFail($id);

        // Подготавливаем данные для ответа
        $data = [
            'typeInfrastruct' => $pageReestrGraph->typeInfrastruct,
            'nameGraph' => $pageReestrGraph->nameGraph,
            'yearAction' => $pageReestrGraph->yearAction,
            'dateCreation' => $pageReestrGraph->dateCreation,
            'dateLastSave' => $pageReestrGraph->dateLastSave,
            'dateArchiv' => $pageReestrGraph->dateArchiv,
            'actor' => $pageReestrGraph->actor,
            'responsible' => $pageReestrGraph->responsible,
            'curator' => $pageReestrGraph->curator,
        ];

        // Возвращаем данные в формате JSON
        return response()->json($data);
    }
}
