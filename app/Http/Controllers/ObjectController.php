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


    // Получение изображения
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

    //Получение документа (СКАЧИВАНИЕ)
    public function downloadDocument($id)
    {
        // Находим запись файла по идентификатору
        $file = CardObjectMainDoc::findOrFail($id);
        // Получаем содержимое файла и отправляем его пользователю для загрузки
        return response()->streamDownload(function () use ($file) {
            echo $file->file_content->getData();
        }, $file->file_name);
    }


    // СОЗДАНИЕ карточки объекта (переход на страницу)
    public function create()
    {
        $breadcrumbs = Breadcrumbs::generate('card-object-create');
        return view('cards/card-object-create', compact('breadcrumbs'));
    }

    // РЕДАКТИРОВАНИЕ карточки объекта (переход на страницу)
    public function edit()
    {
        $breadcrumbs = Breadcrumbs::generate('/card-object/edit');
        return view('cards/card-object-edit', compact('breadcrumbs'));
    }


    //СОХРАНЕНИЕ НОВОЙ карточки объекта (СОЗДАНИЕ)
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
                $content = file_get_contents($image->getRealPath()); // Получение содержимого файла
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
                $serviceData = $service; // Получаем данные об обслуживании
                $selectedColor = $serviceData['selectedColor']; // Получаем выбранный цвет для календаря
                $materials = $serviceData['materials']; // Получаем данные о расходных материалах

                // Создаем новую запись для обслуживания в модели Service
                $newService = new CardObjectServices();
                $newService->service_type = $serviceData['service_type'];
                $newService->short_name = $serviceData['short_name'];
                $newService->performer = $serviceData['performer'];
                $newService->responsible = $serviceData['responsible'];
                $newService->frequency = $serviceData['frequency'];
                $newService->prev_maintenance_date = $serviceData['prev_maintenance_date'];
                $newService->planned_maintenance_date = $serviceData['planned_maintenance_date'];
                $newService->calendar_color = $selectedColor; // Сохраняем выбранный цвет для календаря
                $newService->consumable_materials = $materials; // Сохраняем данные о расходных материалах

                // Связываем обслуживание с карточкой объекта
                $newService->card_object_main_id = $card->id;
                $newService->save();
            }
        }

        // Обработка сохранения видов работ
        if ($request->has('types_of_work')) {
            $typesOfWorkString = $request->types_of_work;
            $typesOfWorkArray = explode(',', $typesOfWorkString); // Разбиваем строку на массив по запятой
            foreach ($typesOfWorkArray as $typeOfWork) {
                // Создаем новую запись для вида работы в модели CardObjectServicesTypes
                $newTypeOfWork = new CardObjectServicesTypes();
                $newTypeOfWork->card_id = $card->id;
                $newTypeOfWork->type_work = $typeOfWork; // Тип работы извлекается из массива, полученного из строки
                // Сохраняем данные о видах работ
                $newTypeOfWork->save();
            }
        }


        // Возвращаем ответ об успешном сохранении данных
        return response()->json(['message' => 'Данные успешно сохранены'], 200);
    }

}
