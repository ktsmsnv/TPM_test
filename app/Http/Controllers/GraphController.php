<?php

namespace App\Http\Controllers;

use App\Models\CardGraph;
use App\Models\CardObjectMainDoc;
use App\Models\CardObjectServices;
use App\Models\CardObjectMain;
use App\Models\CardObjectServicesTypes;
use App\Models\HistoryCardGraph;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use MongoDB\BSON\Binary;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\error;

//контроллер для отображения данных на страницы
class GraphController extends Controller
{

    public function reestrGraphView(Request $request)
    {
        // Получаем все карточки графика
        $cardGraphs = CardGraph::with(['object', 'services'])->get();

//        $allPerformers = [];
//        $allResponsibles = [];
//
//        // Перебираем все карточки графика
//        foreach ($cardGraphs as $object) {
//            // Проверяем наличие связанных записей в cardObjectServices
//            if ($object->cardObjectServices->isNotEmpty()) {
//                // Если есть связанные записи, перебираем их
//                foreach ($object->cardObjectServices as $service) {
//                    // Добавляем данные performer и responsible в соответствующие массивы
//                    $allPerformers[] = $service->performer;
//                    $allResponsibles[] = $service->responsible;
//                }
//            }
//        }
        // Возвращаем представление с передачей данных
        return view('reestrs/reestrGraph', compact('cardGraphs'));
    }

    public function getCardGraph() {
        $user = Auth::user();
        $role = $user->role;
        // Получаем объекты инфраструктуры с их сервисами
        if ($user) {
            // Получаем роль текущего пользователя
            $userRole = $user->role;
            $cardGraphs = CardGraph::with(['object', 'services'])->get();
            // Создаем массив для хранения всех данных
            $formattedGraphs = [];
            // Проходимся по каждому объекту и выбираем все поля
            foreach ($cardGraphs as $cardGraph) {

                // Разделяем cards_ids на массив
                $cardsIds = explode(',', $cardGraph->cards_ids);
                // Создаем массив для хранения всех сервисов
                $allServices = [];
                // Обрабатываем каждый cards_ids отдельно
                foreach ($cardsIds as $cardId) {
                    // Получаем объект инфраструктуры для данного cards_ids
                    $cardObject = CardObjectMain::find($cardId);
                    // Если объект найден, добавляем все его связанные записи CardObjectServices в массив
                    if ($cardObject) {
                        $allServices = array_merge($allServices, $cardObject->services->toArray());
                    }
                }

                foreach ($cardGraph->object as $object) {

                    // Инициализируем флаг для добавления объекта
                    $shouldAddObject = false;

                    // Проверяем роль текущего пользователя и фильтруем записи соответствующим образом
                    if ($userRole == 'executor') {
                        // Проверяем, если текущий пользователь исполнитель
                        foreach ($object->services as $service) {
                            if ($service->performer == $user->name) {
                                $shouldAddObject = true;
                                break;
                            }
                        }
                    } elseif ($userRole == 'responsible') {
                        // Проверяем, если текущий пользователь ответственный
                        foreach ($object->services as $service) {
                            if ($service->responsible == $user->name) {
                                $shouldAddObject = true;
                                break;
                            }
                        }
                    } elseif ($userRole == 'curator' || $userRole == 'admin') {
                        // Если текущий пользователь куратор или администратор, добавляем все объекты
                        $shouldAddObject = true;
                    }

                    // Если объект должен быть добавлен, формируем данные для одного объекта инфраструктуры и его сервисов
                    if ($shouldAddObject) {
                        $formattedGraph = [
                            'id' => $cardGraph->id,
                            'infrastructure_type' => $cardGraph->infrastructure_type,
                            'name' => $cardGraph->name,
                            'curator' => $cardGraph->curator,
                            'year_action' => $cardGraph->year_action,
                            'date_create' => $cardGraph->date_create,
                            'date_last_save' => $cardGraph->date_last_save,
                            'date_archive' => $cardGraph->date_archive,
                            'object' => [
                                'infrastructure' => $cardObject ? $cardObject->infrastructure : null,
                                'name' => $cardObject ? $cardObject->name : null,
                                'number' => $cardObject ? $cardObject->number : null,
                                'location' => $cardObject ? $cardObject->location : null,
                                'date_arrival' => $cardObject ? $cardObject->date_arrival : null,
                                'date_usage' => $cardObject ? $cardObject->date_usage : null,
                                'date_cert_end' => $cardObject ? $cardObject->date_cert_end : null,
                                'date_usage_end' => $cardObject ? $cardObject->date_usage_end : null,
                            ],
                            'services' => array_map(function ($service) {
                                return [
                                    'service_type' => $service['service_type'],
                                    'short_name' => $service['short_name'],
                                    'performer' => $service['performer'],
                                    'responsible' => $service['responsible'],
                                    'frequency' => $service['frequency'],
                                    'prev_maintenance_date' => $service['prev_maintenance_date'],
                                    'planned_maintenance_date' => $service['planned_maintenance_date'],
                                    'calendar_color' => isset($service['calendar_color']) ? $service['calendar_color'] : null,
                                    'consumable_materials' => isset($service['consumable_materials']) ? $service['consumable_materials'] : null,
                                    'work_order' => isset($service['card_work_orders'][0]) ? route('workOrder.show', ['id' => $service['card_work_orders'][0]['_id']]) : null,
                                ];
                            }, $allServices)
                        ];
                        // Добавляем объект к массиву с отформатированными данными
                        $formattedGraphs[] = $formattedGraph;
                    }
                }
        }
        // Возвращаем все данные в формате JSON с правильным заголовком Content-Type
        return response()->json($formattedGraphs);
        }
    }


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
//dd($data_CardGraph);
        // Переда ем данные в представление
        return view('cards/card-graph', compact('data_CardGraph','allObjectsData', 'maintenance'));
    }

    public function getUnlinkedObjectCards()
    {
        // Получаем список карточек объектов, которые не привязаны к другим карточкам графика
        $unlinkedCards = CardObjectMain::whereNull('linked_graph_id')->get();
        // Убедитесь, что все данные в правильной кодировке UTF-8
        foreach ($unlinkedCards as &$card) {
            $card->name = $card->name;
        }

        // Возвращаем список в формате JSON
        return response()->json($unlinkedCards);
    }

    public function addObjectCards(Request $request)
    {
        // Получаем выбранные карточки объектов и ID карточки графика
        $selectedCards = $request->input('selectedCards');
        $graphId = $request->input('graphId');

        // Проверяем, что $selectedCards - массив
        if (!is_array($selectedCards)) {
            return response()->json(['message' => 'Неправильный формат данных'], 400);
        }

        // Обновляем карточку графика, добавляя выбранные карточки объектов к массиву cards_ids
        $graph = CardGraph::findOrFail($graphId);
        $existingCardsIds = $graph->cards_ids ? explode(',', $graph->cards_ids) : [];
        $existingCardsIds = array_merge($existingCardsIds, $selectedCards);
        $graph->cards_ids = implode(',', $existingCardsIds);
        $graph->save();

        // Возвращаем успешный ответ
        return response()->json(['message' => 'Карточки объектов успешно добавлены к карточке графика']);
    }


    // ------------------  СОЗДАНИЕ карточки графика TPM (переход на страницу)  ------------------
    public function createGraphPage(Request $request)
    {
        // Получаем выбранные экземпляры CardObjectMain
        $selectedIds = explode(',', $request->input('ids'));
        $selectedObjectMain = CardObjectMain::whereIn('_id', $selectedIds)->get();

        // Поиск записей CardGraph, где есть хотя бы один объект из $selectedIds
        $CardGraphEntries = CardGraph::where(function($query) use ($selectedIds) {
            foreach ($selectedIds as $id) {
                $query->orWhere('cards_ids', 'like', '%'.$id.'%');
            }
        })->get();
        if ($CardGraphEntries->isNotEmpty()) {
            $existingGraphs = [];
            foreach ($CardGraphEntries as $entry) {
                $existingGraphs[] = [
                    'id' => $entry->_id,
                    'name' => $entry->name,
                    'link' => route('cardGraph', ['id' => $entry->_id]), // Используем именованный маршрут для генерации ссылки
                ];
            }
            $error  = 'Ошибка! Данный объект уже существует в другом графике.';
            return view('cards.card-graph-create', compact('error', 'existingGraphs'));
        }

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
            return response()->json(['error' => 'Карточка графика не найдена'], 404);
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
            return response()->json(['error' => 'Карточка графика не найдена'], 404);
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
