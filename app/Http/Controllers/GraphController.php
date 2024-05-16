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

    public function index($id, Request $request)
    {
//        $data_CardGraph = CardGraph::with('object.services')->findOrFail($id);
        $data_CardGraph =  CardGraph::findOrFail($id);
        $maintenance = [
            ['id' => 1, 'service_type' => 'Регламентные работы', 'short_name' => 'РР'],
            ['id' => 2, 'service_type' => 'Техническое обслуживание', 'short_name' => 'ТО'],
            ['id' => 3, 'service_type' => 'Сервисное техническое обслуживание', 'short_name' => 'СТО'],
            ['id' => 4, 'service_type' => 'Капитальный ремонт', 'short_name' => 'КР'],
            ['id' => 5, 'service_type' => 'Аварийный ремонт', 'short_name' => 'АР'],
        ];

        // Преобразуем строку cards_ids в массив
        $objectIds = explode(',', $data_CardGraph->cards_ids);
//        dd($objectIds);
        // Создаем массив для хранения данных объектов
        $allObjectsData = [];

        // Перебираем все идентификаторы объектов
        foreach($objectIds as $objectId) {
            // Удаляем лишние пробелы
            $objectId = trim($objectId);

            // Получаем объект по идентификатору
            $cardObject = CardObjectMain::with('services')->findOrFail($objectId);

            // Добавляем данные объекта в массив
            $allObjectsData[] = $cardObject;
        }
//dd($objectIds);
        // Передаем данные в представление
        return view('cards/card-graph', compact('data_CardGraph','allObjectsData', 'maintenance'));
    }


    // ------------------  СОЗДАНИЕ карточки графика TPM (переход на страницу)  ------------------
    public function createGraphPage(Request $request)
    {
        // Получаем выбранные экземпляры CardObjectMain
        $selectedIds = explode(',', $request->input('ids'));
        $selectedObjectMain = CardObjectMain::whereIn('_id', $selectedIds)->get();

        // Получаем тип инфраструктуры для первого выбранного объекта
        $infrastructureName = $selectedObjectMain->first()->infrastructure;
        // Получаем количество уже существующих карточек графика для данного типа инфраструктуры
        $count = CardGraph::where('infrastructure_type', $infrastructureName)->count();

        $infrastructureName = mb_strtoupper($infrastructureName);

        // Формируем название карточки графика
        $nameGraph = "ГОДОВОЙ ГРАФИК TPM ОБЪЕКТОВ $infrastructureName ИНФРАСТРУКТУРЫ #" . ($count + 1);
//dd($selectedIds);
        $maintenance = [
            ['id' => 1, 'service_type' => 'Регламентные работы', 'short_name' => 'РР'],
            ['id' => 2, 'service_type' => 'Техническое обслуживание', 'short_name' => 'ТО'],
            ['id' => 3, 'service_type' => 'Сервисное техническое обслуживание', 'short_name' => 'СТО'],
            ['id' => 4, 'service_type' => 'Капитальный ремонт', 'short_name' => 'КР'],
            ['id' => 5, 'service_type' => 'Аварийный ремонт', 'short_name' => 'АР'],
        ];
//        dd($selectedObjectMain);
        return view('cards.card-graph-create', compact('selectedObjectMain', 'nameGraph', 'maintenance', 'selectedIds'));
    }

    //------------------ СОХРАНЕНИЕ НОВОЙ карточки графика (СОЗДАНИЕ) ------------------
    public function saveCardGraph(Request $request)
    {
        // Создаем массив с данными для новой карточки графика
        $data = [
            'name' => $request->input('name'),
            'infrastructure_type' => $request->infrastructure_type,
            'cards_ids' => '"' . $request->input('cards_ids') . '"', // Добавляем кавычки к значению
            'curator' => $request->curator,
            'year_action' => $request->year_action,
            'date_create' => $request->date_create,
            'date_last_save' => $request->date_last_save,
            'date_archive' => $request->date_archive,
        ];

        // Сохраняем карточку графика и получаем ее ID
        $cardId = CardGraph::insertGetId($data);

        // Проверка наличия ID карточки графика
        if ($cardId) {
            // Создаем запись истории и привязываем ее к ID созданной карточки графика
            $history_card = new HistoryCardGraph();
            $history_card->name = $request->input('name');
            $history_card->infrastructure_type = $request->infrastructure_type;
            $history_card->curator = $request->curator;
            $history_card->year_action = $request->year_action;
            $history_card->date_create = $request->date_create;
            $history_card->date_last_save = $request->date_last_save;
            $history_card->date_archive = $request->date_archive;
            $history_card->cards_ids = $request->input('cards_ids'); // Добавляем кавычки к значению
            $history_card->card_graph_id = $cardId;
            $history_card->save();
        }
    }




    // ------------------  РЕДАКТИРОВАНИЕ карточки графика TPM (переход на страницу) ------------------
    public function edit($id)
    {
//        $cardGraph_id = CardGraph::all('_id', 'card_id');
//        $data_CardGraph = CardGraph::where('card_id', $id)->get() and CardGraph::where('_id', $id)->get();
//        $selectedObjectMain = CardObjectMain::where('_id', $id)->get();
////        dd($selectedObjectMain);
//        $data_CardObjectMain = CardObjectMain::with(['graph'])->find($id);

        $data_CardGraph =  CardGraph::findOrFail($id);
//        dd($data_CardGraph);

        $maintenance = [
            ['id' => 1, 'service_type' => 'Регламентные работы', 'short_name' => 'РР'],
            ['id' => 2, 'service_type' => 'Техническое обслуживание', 'short_name' => 'ТО'],
            ['id' => 3, 'service_type' => 'Сервисное техническое обслуживание', 'short_name' => 'СТО'],
            ['id' => 4, 'service_type' => 'Капитальный ремонт', 'short_name' => 'КР'],
            ['id' => 5, 'service_type' => 'Аварийный ремонт', 'short_name' => 'АР'],
        ];
//        dd($selectedObjectMain);
//        $breadcrumbs = Breadcrumbs::generate('/card-graph-edit');
        // Преобразуем строку cards_ids в массив
        $objectIds = explode(',', $data_CardGraph->cards_ids);
//        dd($objectIds);
        // Создаем массив для хранения данных объектов
        $allObjectsData = [];

        // Перебираем все идентификаторы объектов
        foreach($objectIds as $objectId) {
            // Удаляем лишние пробелы
            $objectId = trim($objectId);

            // Получаем объект по идентификатору
            $cardObject = CardObjectMain::with('services')->findOrFail($objectId);

            // Добавляем данные объекта в массив
            $allObjectsData[] = $cardObject;
        }
//dd($objectIds);
        // Передаем данные в представление
        return view('cards/card-graph-edit', compact('data_CardGraph','allObjectsData', 'maintenance'));
    }

    public function editSave(Request $request, $id)
    {
        // Находим карточку объекта по переданному идентификатору
        $card = CardGraph::find($id);
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

        $history_card = new HistoryCardGraph();
        $history_card->name =  $card->name;
        $history_card->infrastructure_type = $card->infrastructure_type;
        $history_card->curator = $request->curator;
        $history_card->year_action = $request->year_action;
        $history_card->date_create = $request->date_create;
        $history_card->date_last_save = $request->date_last_save;
        $history_card->date_archive = $request->date_archive;
        $history_card->cards_ids =  $card->cards_ids;
        $history_card->card_graph_id = $card->card_graph_id;
        $history_card->save();


        // Возвращаем успешный ответ или редирект на страницу карточки объекта
        return response()->json(['success' => 'Данные карточки объекта успешно обновлены'], 200);
    }

    public function archiveGraphDateButt(Request $request)
    {
        $date_archive = Carbon::now()->format('Y-m-d');
        $cardId = $request->id;
        $card = CardGraph::find($cardId);
        // Проверяем, найдена ли карточка
        if (!$card) {
            // Если карточка не найдена, возвращаем ошибку или редирект на страницу ошибки
            return response()->json(['error' => 'Карточка объекта не найдена'], 404);
        }

        // Найдите заказ-наряд по его ID и обновите фактическую дату и статус
        $card->date_archive = $date_archive;
        $card->save();

//        $newWorkOrder_history = new HistoryCardWorkOrder();
//        $newWorkOrder_history->card_id = $card->card_id; // Связываем заказ-наряд с выбранной карточкой объекта
//        $newWorkOrder_history->card_object_services_id = $card->card_object_services_id; // Связываем заказ-наряд с ближайшей услугой
//        $newWorkOrder_history->date_create =  $card->date_create;
//        $newWorkOrder_history->status =  $request->status; // Устанавливаем статус
//        $newWorkOrder_history->date_fact = $card;
//        // Присваиваем номер заказа-наряда
//        $newWorkOrder_history->number = $workOrder->number;
//        $newWorkOrder_history->save();


        return response()->json(['message' => 'Карточка графика успешно заархивирована'], 200);
    }

    // --------------- удаление карточки заказ-наряда ---------------
    public function deleteCardGraph(Request $request)
    {
        $ids = $request->ids;
        // Обновляем записи, устанавливая значение deleted в 1
        foreach ($ids as $id) {
            // Удалить записи из связанных таблиц
            CardGraph::find($id)->delete();
        }

        return response()->json(['success' => true], 200);
    }

}
