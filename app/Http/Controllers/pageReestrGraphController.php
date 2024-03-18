<?php

namespace App\Http\Controllers;


use App\Models\ContractStorage;
use App\Models\pageReestrGraph;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class pageReestrGraphController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $pageReestrGraph = pageReestrGraph::all();

        return view('reestrGraph', compact('pageReestrGraph'));
    }
}
