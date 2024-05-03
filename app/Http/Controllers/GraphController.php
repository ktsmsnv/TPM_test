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
    public function create(Request $request)
    {
//        $selectedIds = explode(',', $request->input('ids'));
//
//        $selectedObjectMain = CardObjectMain::whereIn('_id', $selectedIds)->get();
//        $selectedObjectServices = CardObjectServices::whereIn('card_object_main_id', $selectedIds)->get();
//// Получаем карточки графика и их связанные объекты
//        $cardGraphs = CardGraph::with('object')->get();
//
//        // If you need to access infrastructure property of each selected object
//        $infrastructures = $selectedObjectMain->pluck('infrastructure');
//        // If you need to access all infrastructures as a single collection
//        $allInfrastructures = CardObjectMain::whereIn('_id', $selectedIds)->pluck('infrastructure');
//        dd($infrastructures);

//        $nameGraph =

        // Счетчик для infrastructure

//        $nameGraphCounts = [];
////dd($cardGraphs);
//        // Перебираем карточки графика
//        foreach ($cardGraphs as $cardGraph) {
//            // Получаем связанный объект
//            $cardObjectMain = $cardGraph->cardObjectMain;
//
//            // Получаем значение infrastructure
////            $infrastructure = $selectedObjectMain->infrastructure;
//
//            // Увеличиваем счетчик для данного infrastructure
//            if (isset($nameGraphCounts[$infrastructure])) {
//                $nameGraphCounts[$infrastructure]++;
//            } else {
//                $nameGraphCounts[$infrastructure] = 1;
//            }
//        }
////dd($infrastructure);
//        $nameGraph = '';
//        $infrastructureName = mb_strtoupper($infrastructure);
////dd($infrastructureName);
//        // Проверяем, что $selectedObjectMain не пустой и берем первый элемент
//        if (count($selectedObjectMain) > 0) {
////            if ($nameGraphCounts[$infrastructure] === 1)
//            $count = $nameGraphCounts[$infrastructure];
//
////            dd($count);
//            // Формируем строку $nameGraph
//            $nameGraph = "ГОДОВОЙ ГРАФИК TPM ОБЪЕКТОВ {$infrastructureName} ИНФРАСТРУКТУРЫ #{$count}";
//        } else {
//            $count = $nameGraphCounts['Инфраструктура по умолчанию'] ?? 0;
//            $nameGraph = "ГОДОВОЙ ГРАФИК TPM ОБЪЕКТОВ ИНФРАСТРУКТУРЫ #{$count}";
//        }

//        dd($selectedObjectMain);
        $selectedIds = explode(',', $request->input('ids'));

        // Получаем выбранные экземпляры CardObjectMain
        $selectedObjectMain = CardObjectMain::whereIn('_id', $selectedIds)->get();

        // Получаем все карточки графика
       // $cardGraphs = CardGraph::all();
        $cardGraphs = CardGraph::with('object')->get();


        // Подготавливаем массив для подсчета количества карточек графика для каждого типа инфраструктуры
        $nameGraphCounts = [];

        // Перебираем карточки графика для подсчета количества для каждого типа инфраструктуры
//        foreach ($cardGraphs as $cardGraph) {
//            $cardObjectMain = $cardGraph;
//            // Проверяем, что объект существует, прежде чем получать его инфраструктуру
//            if ($cardObjectMain) {
//                $infrastructure = $cardObjectMain->infrastructure;
//                if (isset($nameGraphCounts[$infrastructure])) {
//                    $nameGraphCounts[$infrastructure]++;
//                } else {
//                    $nameGraphCounts[$infrastructure] = 1;
//                }
//            }
//
//        }
        // Перебираем все карточки графика, чтобы посчитать количество карточек для каждого типа инфраструктуры
        foreach ($cardGraphs as $cardGraph) {
            foreach ($cardGraph->object as $cardObjectMain) {
                $infrastructure = $cardObjectMain->infrastructure;
                if (isset($nameGraphCounts[$infrastructure])) {
                    $nameGraphCounts[$infrastructure]++;
                } else {
                    $nameGraphCounts[$infrastructure] = 1;
                }
            }
        }
//dd($cardObjectMain);

        // Переменная для хранения следующего порядкового числа для новой записи
        //$count = $nameGraphCounts['Инфраструктура по умолчанию'] ?? 1;
//dd($count);
        // Формируем название в зависимости от типа инфраструктуры
        // Это делается перед передачей на страницу
        $nameGraph = [];
//        foreach ($selectedObjectMain as $cardObjectMain) {
//            $infrastructure = $cardObjectMain->infrastructure;
//            $infrastructureName = mb_strtoupper($infrastructure);
//            $nameGraph[$cardObjectMain->_id] = "ГОДОВОЙ ГРАФИК TPM ОБЪЕКТОВ {$infrastructureName} ИНФРАСТРУКТУРЫ #{$count}";
//            $count++;
//        }
        foreach ($selectedObjectMain as $cardObjectMain) {
            $infrastructure = $cardObjectMain->infrastructure;
            $infrastructureName = mb_strtoupper($infrastructure);
            $count = $nameGraphCounts[$infrastructure] ?? 0; // Получаем количество уже существующих карточек графика для данного типа инфраструктуры
            //dd($nameGraphCounts[$infrastructure]);
            $nameGraph[$cardObjectMain->_id] = "ГОДОВОЙ ГРАФИК TPM ОБЪЕКТОВ {$infrastructureName} ИНФРАСТРУКТУРЫ #{$count}";
            $count++; // Увеличиваем количество для новой карточки графика
        }
        $nameGraphString = $nameGraph[$cardObjectMain->_id];
//dd($nameGraph);
        $maintenance = [
            ['id' => 1, 'service_type' => 'Регламентные работы', 'short_name' => 'РР'],
            ['id' => 2, 'service_type' => 'Техническое обслуживание', 'short_name' => 'ТО'],
            ['id' => 3, 'service_type' => 'Сервисное техническое обслуживание', 'short_name' => 'СТО'],
            ['id' => 4, 'service_type' => 'Капитальный ремонт', 'short_name' => 'КР'],
            ['id' => 5, 'service_type' => 'Аварийный ремонт', 'short_name' => 'АР'],
        ];

//        dd($maintenance);
        $breadcrumbs = Breadcrumbs::generate('card-graph-create');
        return view('cards/card-graph-create', compact('selectedIds', 'selectedObjectMain',
            'maintenance', 'breadcrumbs', 'nameGraphCounts', 'nameGraph', 'nameGraphString'));
    }

    //------------------ СОХРАНЕНИЕ НОВОЙ карточки графика (СОЗДАНИЕ) ------------------
    public function saveData(Request $request, $id)
    {
        // Получение данных из запроса
        $data = $request->all();
        $card = CardObjectMain::find($id);


        // Проверка наличия данных
        if (!empty($data)) {
            // Создание новой записи в модели CardGraph
            $cardGraph = new CardGraph();

            $cardGraph->curator = $data['curator'] ?? null;
            $cardGraph->year_action = $data['year_action'] ?? null;
            $cardGraph->date_create = $data['date_create'] ?? null;
            $cardGraph->date_last_save = $data['date_last_save'] ?? null;
            $cardGraph->date_archive = $data['date_archive'] ?? null;
//            $cardGraph->nameGraph = $data['nameGraph'] ?? null;
            // Определение значения nameGraph в зависимости от типа инфраструктуры выбранного объекта
            $cardObjectMain = CardObjectMain::find($data['card_id']);
            $infrastructure = $cardObjectMain->infrastructure;
            $infrastructureName = mb_strtoupper($infrastructure);
            $count = CardGraph::whereHas('object', function ($query) use ($infrastructure) {
                    $query->where('infrastructure', $infrastructure);
                })->count() + 1;
            $cardGraph->nameGraph = "ГОДОВОЙ ГРАФИК TPM ОБЪЕКТОВ {$infrastructureName} ИНФРАСТРУКТУРЫ #{$count}";

            // Предполагается, что card_id передается в запросе, в противном случае нужно уточнить как получить этот ID
            $cardGraph->card_id = $card->id ?? null;

            // Сохранение данных
            $cardGraph->save();

            // Редирект на другую страницу или что-то еще
            return redirect()->back()->with('success', 'Данные успешно сохранены');
        } else {
            // Если данных нет, возвращаем ошибку или что-то еще
            return redirect()->back()->with('error', 'Нет данных для сохранения');
        }
//        ['infrastructure', 'curator', 'year_action', 'date_create', 'date_last_save', 'date_archive'];
//        // Обработка сохранения основных данных карточки графика
//        $card = new CardGraph();
//        $card->infrastructure = $request->infrastructure;
//        $card->curator = $request->curator;
//        $card->year_action = $request->year_action;
//        $card->date_create = $request->date_create;
//        $card->date_last_save = $request->date_last_save;
//        $card->date_archive = $request->date_archive;
//        // Сохранение основных данных карточки
//        $card->save();
//
//
//        // Обработка сохранения данных об обслуживаниях
//        if ($request->has('services')) {
//            $services = $request->services;
//            foreach ($services as $service) {
//                // Получаем данные об обслуживании
//                $serviceType = $service['service_type'];
//                $shortName = $service['short_name'];
//                $performer = $service['performer'];
//                $responsible = $service['responsible'];
//                $frequency = $service['frequency'];
//                $prevMaintenanceDate = $service['prev_maintenance_date'];
//                $plannedMaintenanceDate = $service['planned_maintenance_date'];
//                $selectedColor = $service['selectedColor'];
//                $materials = $service['materials'];
//
//                // Получаем виды работ
//                $typesOfWork = $service['types_of_work'] ?? [];
//
//                // Создаем новую запись для обслуживания в модели CardObjectServices
//                $newService = new CardObjectServices();
//                $newService->service_type = $serviceType;
//                $newService->short_name = $shortName;
//                $newService->performer = $performer;
//                $newService->responsible = $responsible;
//                $newService->frequency = $frequency;
//                $newService->prev_maintenance_date = $prevMaintenanceDate;
//                $newService->planned_maintenance_date = $plannedMaintenanceDate;
//                $newService->calendar_color = $selectedColor;
//                $newService->consumable_materials = $materials;
//                $newService->card_object_main_id = $card->id;
//                $newService->save();
//
//                // Сохраняем варианты работ
//                foreach ($typesOfWork as $typeOfWork) {
//                    $newTypeOfWork = new CardObjectServicesTypes();
//                    $newTypeOfWork->card_id = $card->id;
//                    $newTypeOfWork->card_services_id = $newService->id;
//                    $newTypeOfWork->type_work = $typeOfWork;
//                    $newTypeOfWork->save();
//                }
//            }
//        }
//
//
//
//        // Возвращаем ответ об успешном сохранении данных
//        return response()->json(['message' => 'Данные успешно сохранены'], 200);
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
