<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use App\Models\CardObjectMain;
use App\Models\CardObjectMainDoc;
use App\Models\CardObjectServices;
use App\Models\CardObjectServicesTypes;
use MongoDB\BSON\Binary;

class ObjectController extends Controller
{

//    public function index($id)
//    {
//        $data_CardObjectMain = CardObjectMain::find($id);
//        $breadcrumbs = Breadcrumbs::generate('card-object');
//        return view('cards/card-object', compact('breadcrumbs', 'data_CardObjectMain'));
//    }
    public function index($id)
    {
        $data_CardObjectMain = CardObjectMain::find($id);
        $data_CardObjectMainDocs = CardObjectMainDoc::where('card_object_main_id', $id)->get();
        return view('cards/card-object', compact('data_CardObjectMain', 'data_CardObjectMainDocs'));
    }


    // ------------------  Получение изображения  --------------------------
    public function getImage($id)
    {
        // Получаем объект CardObjectMain по его id
        $cardObject = CardObjectMain::find($id);
        // Проверяем, существует ли такой объект и содержит ли он изображение
        if ($cardObject && $cardObject->image) {
            // Получаем бинарные данные изображения
            $binaryData = $cardObject->image;
            // Определяем тип бинарных данных
            $contentType = finfo_buffer(finfo_open(), $binaryData->getData(), FILEINFO_MIME_TYPE);
            // Возвращаем содержимое изображения с правильным заголовком
            return response($binaryData->getData(), 200)
                ->header('Content-Type', $contentType);
        } else {
            // Возвращаем пустое изображение или другой альтернативный контент
            abort(404);
        }
    }

    // ------------------  Получение документа (СКАЧИВАНИЕ)  ------------------
    public function downloadDocument($id)
    {
        // Находим запись файла по идентификатору
        $file = CardObjectMainDoc::findOrFail($id);
        // Получаем содержимое файла и отправляем его пользователю для загрузки
        return response()->streamDownload(function () use ($file) {
            echo $file->file_content->getData();
        }, $file->file_name);
    }


    // ------------------  СОЗДАНИЕ карточки объекта (переход на страницу)  ------------------
    public function create()
    {
        $breadcrumbs = Breadcrumbs::generate('card-object-create');
        return view('cards/card-object-create', compact('breadcrumbs'));
    }

    // ------------------  РЕДАКТИРОВАНИЕ карточки объекта (переход на страницу) ------------------
    public function edit()
    {
        $breadcrumbs = Breadcrumbs::generate('/card-object/edit');
        return view('cards/card-object-edit', compact('breadcrumbs'));
    }


    //------------------ СОХРАНЕНИЕ НОВОЙ карточки объекта (СОЗДАНИЕ) ------------------
    public function saveData(Request $request)
    {
        // Обработка сохранения основных данных карточки
        $card = new CardObjectMain();
        $card->infrastructure = $request->infrastructure;
        $card->name = $request->name;
        $card->number = $request->number;
        $card->location = $request->location;
        $card->date_arrival = $request->date_arrival;
        $card->date_usage = $request->date_usage;
        $card->date_cert_end = $request->date_cert_end;
        $card->date_usage_end = $request->date_usage_end;
        // Сохранение основных данных карточки
        $card->save();
        // Обработка сохранения изображений
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $content = file_get_contents($image->getRealPath()); // Получение содержимого файа
                $binaryData = new Binary($content, Binary::TYPE_GENERIC); // Создание объекта Binary с двоичными данными

                // Присваиваем двоичные данные к полю image модели CardObjectMain
                $card->image = $binaryData;
                $card->save();
                // Сохраняем файл в папку на сервере
                $path = $image->storeAs('public/images', $image->getClientOriginalName());
            }
        }
        // Обработка сохранения файлов документов
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $content = file_get_contents($file->getRealPath()); // Получение содержимого файла
                $binaryData = new Binary($content, Binary::TYPE_GENERIC); // Создание объекта Binary с двоичными данными

                // Создаем новую запись для файла в модели CardObjectMainDoc
                $doc = new CardObjectMainDoc();
                $doc->file_content = $binaryData; // Присваиваем двоичные данные к полю file_content модели CardObjectMainDoc
                $doc->file_name = $file->getClientOriginalName();
                $doc->card_object_main_id = $card->id;
                $doc->save();
                // Сохраняем файл в папку на сервере
                $path = $file->storeAs('public/files', $file->getClientOriginalName());
            }
        }


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
