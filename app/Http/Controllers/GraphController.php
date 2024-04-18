<?php

namespace App\Http\Controllers;

use App\Models\CardGraphMain;
use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use MongoDB\BSON\Binary;

//контроллер для отображения данных на страницы
class GraphController extends Controller
{

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('card-graph');
        return view('cards/card-graph', compact( 'breadcrumbs'));
    }


    // ------------------  СОЗДАНИЕ карточки графика TPM (переход на страницу)  ------------------
    public function create(Request $request)
    {
        $selectedIds = $request->input('ids');
//        $selectedObjects = Object::whereIn('id', $selectedIds)->get();
        $breadcrumbs = Breadcrumbs::generate('card-graph-create');
        return view('cards/card-graph-create', compact('selectedIds','breadcrumbs'));
    }

    // ------------------  РЕДАКТИРОВАНИЕ карточки графика TPM (переход на страницу) ------------------
    public function edit()
    {
        $breadcrumbs = Breadcrumbs::generate('/card-graph/edit');
        return view('cards/card-graph-edit', compact('breadcrumbs'));
    }

    //------------------ СОХРАНЕНИЕ НОВОЙ карточки объекта (СОЗДАНИЕ) ------------------
    public function saveData(Request $request)
    {
        ['infrastructure', 'curator', 'year_action', 'date_create', 'date_last_save', 'date_archive'];
        // Обработка сохранения основных данных карточки графика
        $card = new CardGraphMain();
        $card->infrastructure = $request->infrastructure;
        $card->curator = $request->curator;
        $card->year_action = $request->year_action;
        $card->date_create = $request->date_create;
        $card->date_last_save = $request->date_last_save;
        $card->date_archive = $request->date_archive;
        // Сохранение основных данных карточки
        $card->save();


        // Обработка сохранения данных об обслуживаниях
        if ($request->has('services')) {
            $services = $request->services;
            foreach ($services as $service) {
                // Получаем данные об обслуживании
                $serviceType = $service['service_type'];
                $shortName = $service['short_name'];
                $performer = $service['performer'];
                $responsible = $service['responsible'];
                $frequency = $service['frequency'];
                $prevMaintenanceDate = $service['prev_maintenance_date'];
                $plannedMaintenanceDate = $service['planned_maintenance_date'];
                $selectedColor = $service['selectedColor'];
                $materials = $service['materials'];

                // Получаем виды работ
                $typesOfWork = $service['types_of_work'] ?? [];

                // Создаем новую запись для обслуживания в модели CardObjectServices
                $newService = new CardObjectServices();
                $newService->service_type = $serviceType;
                $newService->short_name = $shortName;
                $newService->performer = $performer;
                $newService->responsible = $responsible;
                $newService->frequency = $frequency;
                $newService->prev_maintenance_date = $prevMaintenanceDate;
                $newService->planned_maintenance_date = $plannedMaintenanceDate;
                $newService->calendar_color = $selectedColor;
                $newService->consumable_materials = $materials;
                $newService->card_object_main_id = $card->id;
                $newService->save();

                // Сохраняем варианты работ
                foreach ($typesOfWork as $typeOfWork) {
                    $newTypeOfWork = new CardObjectServicesTypes();
                    $newTypeOfWork->card_id = $card->id;
                    $newTypeOfWork->card_services_id = $newService->id;
                    $newTypeOfWork->type_work = $typeOfWork;
                    $newTypeOfWork->save();
                }
            }
        }



        // Возвращаем ответ об успешном сохранении данных
        return response()->json(['message' => 'Данные успешно сохранены'], 200);
    }

}
