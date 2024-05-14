<?php

namespace App\Http\Controllers;

use App\Models\CardGraph;
use App\Models\CardObjectMainDoc;
use App\Models\CardObjectServices;
use App\Models\CardObjectMain;
use App\Models\CardObjectServicesTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use MongoDB\BSON\Binary;

//контроллер для отображения данных на страницы
class GraphController extends Controller
{

    public function index($id)
    {
        $data_CardGraph = CardGraph::all();
        $data_CardObjectMain = CardObjectMain::with(['graph'])->find($id);
//        $data_CardGraph = CardGraph::find('card_id');
//dd($data_CardGraph);
//        dd($data_CardObjectMain);
        $selectedObjectMain = CardObjectMain::where('_id', $id)->get();

        $selectedObjectServices = CardObjectServices::where('card_object_main_id', $id)->get();
        $maintenance = [
            ['id' => 1, 'service_type' => 'Регламентные работы', 'short_name' => 'РР'],
            ['id' => 2, 'service_type' => 'Техническое обслуживание', 'short_name' => 'ТО'],
            ['id' => 3, 'service_type' => 'Сервисное техническое обслуживание', 'short_name' => 'СТО'],
            ['id' => 4, 'service_type' => 'Капитальный ремонт', 'short_name' => 'КР'],
            ['id' => 5, 'service_type' => 'Аварийный ремонт', 'short_name' => 'АР'],
        ];

        return view('cards/card-graph', compact( 'data_CardObjectMain', 'data_CardGraph',
            'selectedObjectMain', 'selectedObjectServices', 'maintenance'));
    }


    // ------------------  СОЗДАНИЕ карточки графика TPM (переход на страницу)  ------------------
    public function createGraphPage(Request $request)
    {
        // Получаем выбранные экземпляры CardObjectMain
        $selectedIds = explode(',', $request->input('ids'));
        $selectedObjectMain = CardObjectMain::whereIn('_id', $selectedIds)->get();

        // Получаем тип инфраструктуры для первого выбранного объекта
        $infrastructureName = strtoupper($selectedObjectMain->first()->infrastructure);

        // Получаем количество уже существующих карточек графика для данного типа инфраструктуры
        $count = CardGraph::where('infrastructure_type', $infrastructureName)->count();

        // Формируем название карточки графика
        $nameGraph = "ГОДОВОЙ ГРАФИК TPM ОБЪЕКТОВ $infrastructureName ИНФРАСТРУКТУРЫ #" . ($count + 1);

        $maintenance = [
            ['id' => 1, 'service_type' => 'Регламентные работы', 'short_name' => 'РР'],
            ['id' => 2, 'service_type' => 'Техническое обслуживание', 'short_name' => 'ТО'],
            ['id' => 3, 'service_type' => 'Сервисное техническое обслуживание', 'short_name' => 'СТО'],
            ['id' => 4, 'service_type' => 'Капитальный ремонт', 'short_name' => 'КР'],
            ['id' => 5, 'service_type' => 'Аварийный ремонт', 'short_name' => 'АР'],
        ];

        return view('cards.card-graph-create', compact('selectedObjectMain', 'nameGraph', 'maintenance', 'selectedIds'));
    }

    //------------------ СОХРАНЕНИЕ НОВОЙ карточки графика (СОЗДАНИЕ) ------------------
    public function saveCardGraph(Request $request)
    {
        $data = $request->validate([
            'curator' => 'nullable',
            'year_action' => 'nullable|integer',
            'date_create' => 'required|date',
            'date_last_save' => 'required|date',
            'date_archive' => 'nullable|date',
        ]);
        $data['name'] = $request->input('name');
        $data['infrastructure_type'] = $request->input('infrastructure_type');
        $data['cards_ids'] = $request->input('cards_ids');

        $cardGraph = CardGraph::create($data);

        // Редирект или что-то еще
    }


    // ------------------  РЕДАКТИРОВАНИЕ карточки графика TPM (переход на страницу) ------------------
    public function edit($id)
    {
//        $cardGraph_id = CardGraph::all('_id', 'card_id');
//        $data_CardGraph = CardGraph::where('card_id', $id)->get() and CardGraph::where('_id', $id)->get();
        $selectedObjectMain = CardObjectMain::where('_id', $id)->get();
//        dd($selectedObjectMain);
        $data_CardObjectMain = CardObjectMain::with(['graph'])->find($id);

        $data_CardGraph = CardGraph::with(['object'])->find('_id');
        dd($data_CardGraph);

        $maintenance = [
            ['id' => 1, 'service_type' => 'Регламентные работы', 'short_name' => 'РР'],
            ['id' => 2, 'service_type' => 'Техническое обслуживание', 'short_name' => 'ТО'],
            ['id' => 3, 'service_type' => 'Сервисное техническое обслуживание', 'short_name' => 'СТО'],
            ['id' => 4, 'service_type' => 'Капитальный ремонт', 'short_name' => 'КР'],
            ['id' => 5, 'service_type' => 'Аварийный ремонт', 'short_name' => 'АР'],
        ];
//        dd($selectedObjectMain);
//        $breadcrumbs = Breadcrumbs::generate('/card-graph-edit');
        return view('cards/card-graph-edit', compact('data_CardObjectMain', 'selectedObjectMain', 'maintenance', 'data_CardGraph'));
    }

    public function editSave(Request $request, $id)
    {
        // Находим карточку объекта по переданному идентификатору
        $card = CardGraph::where('_id', $id)->get();
        // Проверяем, найдена ли карточка
        if (!$card) {
            // Если карточка не найдена, возвращаем ошибку или редирект на страницу ошибки
            return response()->json(['error' => 'Карточка объекта не найдена'], 404);
        }

        // Обновляем основные данные карточки объекта
//        $card->infrastructure = $request->infrastructure;
        $card->curator = $request->curator;
        $card->year_action = $request->year_action;
        $card->date_create = $request->date_create;
        $card->date_last_save = $request->date_last_save;
        $card->date_archive = $request->date_archive;

        // Сохраняем изменения
        $card->save();



        // Возвращаем успешный ответ или редирект на страницу карточки объекта
        return response()->json(['success' => 'Данные карточки объекта успешно обновлены'], 200);
    }

}
