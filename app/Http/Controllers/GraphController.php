<?php

namespace App\Http\Controllers;

use App\Models\CardGraph;
use App\Models\CardObjectMainDoc;
use App\Models\CardObjectServices;
use App\Models\CardObjectMain;
use App\Models\CardObjectServicesTypes;
use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use MongoDB\BSON\Binary;

//контроллер для отображения данных на страницы
class GraphController extends Controller
{

    public function index($id)
    {
        $data_CardGraph = CardGraph::all();
        $data_CardObjectMain = CardObjectMain::with(['graph'])->find($id);

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
        $selectedIds = explode(',', $request->input('ids'));
        $selectedObjectMain = CardObjectMain::whereIn('_id', $selectedIds)->get();
        $selectedObjectServices = CardObjectServices::whereIn('card_object_main_id', $selectedIds)->get();
//        dd($selectedIds);
        $maintenance = [
            ['id' => 1, 'service_type' => 'Регламентные работы', 'short_name' => 'РР'],
            ['id' => 2, 'service_type' => 'Техническое обслуживание', 'short_name' => 'ТО'],
            ['id' => 3, 'service_type' => 'Сервисное техническое обслуживание', 'short_name' => 'СТО'],
            ['id' => 4, 'service_type' => 'Капитальный ремонт', 'short_name' => 'КР'],
            ['id' => 5, 'service_type' => 'Аварийный ремонт', 'short_name' => 'АР'],
        ];

//        dd($maintenance);
        $breadcrumbs = Breadcrumbs::generate('card-graph-create');
        return view('cards/card-graph-create', compact('selectedIds', 'selectedObjectMain', 'selectedObjectServices', 'maintenance', 'breadcrumbs'));
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


    //------------------ СОХРАНЕНИЕ НОВОЙ карточки объекта (СОЗДАНИЕ) ------------------
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

}
