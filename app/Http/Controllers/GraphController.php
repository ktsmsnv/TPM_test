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
//        dd($data_CardGraph->cura tor);
//        dd($data_CardObjectMain);
        return view('cards/card-graph', compact( 'data_CardObjectMain', 'data_CardGraph'));
    }


    // ------------------  СОЗДАНИЕ карточки графика TPM (переход на страницу)  ------------------
    public function create(Request $request)
    {
        $selectedIds = explode(',', $request->input('ids'));
        $selectedObjectMain = CardObjectMain::whereIn('_id', $selectedIds)->get();
        $selectedObjectServices = CardObjectServices::whereIn('card_object_main_id', $selectedIds)->get();
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
    public function edit()
    {
        $breadcrumbs = Breadcrumbs::generate('/card-graph/edit');
        return view('cards/card-graph-edit', compact('breadcrumbs'));
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
