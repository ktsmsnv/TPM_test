<?php

namespace App\Http\Controllers;

use App\Models\CardGraph;
use App\Models\HistoryCardObjectMain;
use App\Models\HistoryCardObjectMainDoc;
use App\Models\HistoryCardObjectServices;
use App\Models\HistoryCardObjectServicesTypes;
use App\Models\User;
use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use App\Models\CardObjectMain;
use App\Models\CardObjectMainDoc;
use App\Models\CardObjectServices;
use App\Models\CardObjectServicesTypes;
use Illuminate\Support\Facades\Log;
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
        $data_CardObjectMain = CardObjectMain::with(['services', 'services.services_types'])->find($id);
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
    public function getUsersByRole($role)
    {
        // Получаем пользователей с указанной ролью
        $users = User::where('role', $role)->get();

        return $users;
    }
    public function create()
    {
        // Получаем пользователей с ролью 'executor'
        $executors = $this->getUsersByRole('executor');

        // Получаем пользователей с ролью 'responsible'
        $responsibles = $this->getUsersByRole('responsible');

        $curators = User::all();

        $breadcrumbs = Breadcrumbs::generate('card-object-create');
        return view('cards/card-object-create', compact('breadcrumbs', 'executors', 'responsibles', 'curators'));
    }

    // ------------------  РЕДАКТИРОВАНИЕ карточки объекта (переход на страницу) ------------------
    public function edit($id)
    {        // Получаем пользователей с ролью 'executor'
        $executors = $this->getUsersByRole('executor');

        // Получаем пользователей с ролью 'responsible'
        $responsibles = $this->getUsersByRole('responsible');

        $curators = User::all();
        $data_CardObjectMain = CardObjectMain::find($id);
        $data_CardObjectMainDocs = CardObjectMainDoc::where('card_object_main_id', $id)->get();
//        dd($data_CardObjectMainDocs);
//        $breadcrumbs = Breadcrumbs::generate('/card-object/edit');
        return view('cards/card-object-edit', compact('data_CardObjectMain', 'data_CardObjectMainDocs', 'executors', 'responsibles', 'curators'));
    }


    //------------------ СОХРАНЕНИЕ НОВОЙ карточки объекта (СОЗДАНИЕ) ------------------
    public function saveData(Request $request)
    {
        // Обработка сохранения основных данных карточки
        $card = new CardObjectMain();
        $card->infrastructure = $request->infrastructure;
        $card->curator = $request->curator;
        $card->name = $request->name;
        $card->number = $request->number;
        $card->location = $request->location;
        $card->date_arrival = $request->date_arrival;
        $card->date_usage = $request->date_usage;
        $card->date_cert_end = $request->date_cert_end;
        $card->date_usage_end = $request->date_usage_end;
//        $card->deleted = 0;
        // Сохранение основных данных карточки
        $card->save();
        $cardId = $card->id;
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
            $services = json_decode($request->services, true); // Преобразуем JSON в массив
            foreach ($services as $service) {
                // Создаем новую запись для обслуживания в модели CardObjectServices
                $newService = new CardObjectServices();
                $newService->service_type = $service['service_type'];
                $newService->short_name = $service['short_name'];
                $newService->performer = $service['performer'];
                $newService->responsible = $service['responsible'];
                $newService->frequency = $service['frequency'];
               // $newService->prev_maintenance_date = $service['prev_maintenance_date'];
                // Проверяем, была ли передана дата предыдущего обслуживания
                if (isset($service['prev_maintenance_date'])) {
                    $newService->prev_maintenance_date = $service['prev_maintenance_date'];
                } else {
                    $newService->prev_maintenance_date = null; // Устанавливаем значение null
                }
                $newService->planned_maintenance_date = $service['planned_maintenance_date'];
                // Если частота "Сменное", задаем цвет по умолчанию
                if ($service['frequency'] === 'Сменное') {
                    $newService->calendar_color = '#fff';
                } else {
                    $newService->calendar_color = $service['selectedColor'];
                }
                $newService->consumable_materials = $service['materials'];
                $newService->checked = $service['checked'];
                $newService->card_object_main_id = $cardId;
                $newService->save();
                $serviceId = $newService->id;

                // Получаем данные о виде работ для текущего обслуживания
                $typesOfWork = $service['types_of_work'];
//                dd($typesOfWork);
                foreach ($typesOfWork as $typeOfWork) {
                    CardObjectServicesTypes::create([
                        'card_id' => $cardId,
                        'card_services_id' => $serviceId,
                        'type_work' => $typeOfWork,
                    ]);
                }
            }
        }


        // ------------ ИСТОРИЯ ------------
        $history_card = new HistoryCardObjectMain();
        $history_card->infrastructure = $request->infrastructure;
        $history_card->curator = $request->curator;
        $history_card->name = $request->name;
        $history_card->number = $request->number;
        $history_card->location = $request->location;
        $history_card->date_arrival = $request->date_arrival;
        $history_card->date_usage = $request->date_usage;
        $history_card->date_cert_end = $request->date_cert_end;
        $history_card->date_usage_end = $request->date_usage_end;
        $history_card->card_id = $card->id;
        // Сохранение основных данных карточки
        $history_card->save();
        $history_cardId = $history_card->id;
        // Обработка сохранения изображений
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $content = file_get_contents($image->getRealPath()); // Получение содержимого файа
                $binaryData = new Binary($content, Binary::TYPE_GENERIC); // Создание объекта Binary с двоичными данными

                // Присваиваем двоичные данные к полю image модели CardObjectMain
                $history_card->image = $binaryData;
                $history_card->save();
            }
        }
        // Обработка сохранения файлов документов
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $content = file_get_contents($file->getRealPath()); // Получение содержимого файла
                $binaryData = new Binary($content, Binary::TYPE_GENERIC); // Создание объекта Binary с двоичными данными

                // Создаем новую запись для файла в модели CardObjectMainDoc
                $history_doc = new HistoryCardObjectMainDoc();
                $history_doc->file_content = $binaryData; // Присваиваем двоичные данные к полю file_content модели CardObjectMainDoc
                $history_doc->file_name = $file->getClientOriginalName();
                $history_doc->card_object_main_id = $history_card->id;
                $history_doc->save();
            }
        }

        // Обработка сохранения данных об обслуживаниях
        if ($request->has('services')) {
            $services = json_decode($request->services, true); // Преобразуем JSON в массив
            foreach ($services as $service) {
                // Создаем новую запись для обслуживания в модели CardObjectServices
                $history_newService = new HistoryCardObjectServices();
                $history_newService->service_type = $service['service_type'];
                $history_newService->short_name = $service['short_name'];
                $history_newService->performer = $service['performer'];
                $history_newService->responsible = $service['responsible'];
                $history_newService->frequency = $service['frequency'];
                $history_newService->prev_maintenance_date = $service['prev_maintenance_date'];
                $history_newService->planned_maintenance_date = $service['planned_maintenance_date'];
                // Если частота "Сменное", задаем цвет по умолчанию
                if ($service['frequency'] === 'Сменное') {
                    $history_newService->calendar_color = '#fff';
                } else {
                    $history_newService->calendar_color = $service['selectedColor'];
                }
                $history_newService->consumable_materials = $service['materials'];
                $history_newService->checked = $service['checked'];
                $history_newService->card_object_main_id = $history_card->id;
                $history_newService->save();
                $history_serviceId = $history_newService->id;

                // Получаем данные о виде работ для текущего обслуживания
                $history_typesOfWork = $service['types_of_work'];
//                dd($typesOfWork);
                foreach ($history_typesOfWork as $history_typeOfWork) {
                    HistoryCardObjectServicesTypes::create([
                        'card_id' => $history_cardId,
                        'card_services_id' => $history_serviceId,
                        'type_work' => $history_typeOfWork,
                    ]);
                }
            }
        }
        // Возвращаем идентификатор созданной карточки в формате JSON
        return response()->json(['id' => $cardId]);
    }

    //------------------ СОХРАНЕНИЕ ИЗМЕНЕНИЙ карточки объекта (РЕДАКТИРОВАНИЕ) ------------------
    public function editSave(Request $request, $id)
    {
        // Находим карточку объекта по переданному идентификатору
        $card = CardObjectMain::find($id);

        // Проверяем, найдена ли карточка
        if (!$card) {
            // Если карточка не найдена, возвращаем ошибку или редирект на страницу ошибки
            return response()->json(['error' => 'Карточка объекта не найдена'], 404);
        }

        // Обновляем основные данные карточки объекта
        $card->infrastructure = $request->infrastructure;
        $card->name = $request->name;
        $card->curator = $request->curator;
        $card->number = $request->number;
        $card->location = $request->location;
        $card->date_arrival = $request->date_arrival;
        $card->date_usage = $request->date_usage;
        $card->date_cert_end = $request->date_cert_end;
        $card->date_usage_end = $request->date_usage_end;

        // Сохраняем изменения
        $card->save();

        // Обновляем куратора во всех связанных карточках графиков
        $this->updateCuratorInGraphs($card->_id, $card->curator);

        // Обновляем изображения (если есть новые изображения)
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

        // удаляем документы
        if ($request->has('files_delete')) {
            // Получаем список файлов для удаления
            $filesToDelete = $request->input('files_delete');

            // Удаляем документы, если есть файлы для удаления
            if (!empty($filesToDelete)) {
                CardObjectMainDoc::whereIn('file_name', $filesToDelete)->delete();
            }
        }
        // Обновляем документы (если есть новые документы)
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

// Обновляем данные об обслуживаниях (обновляем существующие и добавляем новые)
        if ($request->has('services')) {
            //dd($request->services);
            $servicesData = json_decode($request->services, true);

            foreach ($servicesData as $service) {
                // Проверяем, существует ли ключ 'id' в массиве $service
                if (array_key_exists('id', $service)) {
                    // Находим соответствующую существующую услугу, если она существует
                    $existingService = CardObjectServices::where('_id', $service['id'])->first();

                    if ($existingService) {
                        // Обновляем существующую услугу
                        $existingService->update([
                            'service_type' => $service['service_type'],
                            'short_name' => $service['short_name'],
                            'performer' => $service['performer'],
                            'responsible' => $service['responsible'],
                            'frequency' => $service['frequency'],
                            'prev_maintenance_date' => $service['prev_maintenance_date'],
                            'planned_maintenance_date' => $service['planned_maintenance_date'],
                            'calendar_color' => $service['selectedColor'],
                            'consumable_materials' => $service['materials'],
                            'checked' => $service['checked'],
                        ]);

                        // Обновляем или создаем виды работ для текущего обслуживания
                        if (isset($service['types_of_work'])) {
                            foreach ($service['types_of_work'] as $typeOfWork) {
                                if (isset($typeOfWork['id'])) {
                                    $existingTypeOfWork = CardObjectServicesTypes::find($typeOfWork['id']);
                                    if ($existingTypeOfWork) {
                                        $existingTypeOfWork->update(['type_work' =>  $typeOfWork['value']]);
                                    }
                                } else {
                                    // Создаем новый вид работы
                                    CardObjectServicesTypes::create([
                                        'card_id' => $id,
                                        'card_services_id' => $existingService->id,
                                        'type_work' =>  $typeOfWork['value']
                                    ]);
                                }
                            }
                        }
                    }

                } else {
                    // Создаем новую запись об обслуживании
                    $newService = new CardObjectServices();
                    $newService->fill($service); // Заполняем модель данными из массива
                    $newService->card_object_main_id = $id;
                    $newService->save();

                    // Создаем виды работ для новой услуги
                    if (isset($service['types_of_work'])) {
                        foreach ($service['types_of_work'] as $typeOfWork) {
                            CardObjectServicesTypes::create([
                                'card_id' => $id,
                                'card_services_id' => $newService->id,
                                'type_work' =>  $typeOfWork['value']
                            ]);
                        }
                    }
                }
            }
        }
        // удаляем типы работ
        if ($request->has('types_of_work_delete')) {
            // Получаем список типов работ для удаления
            $typesOfWorkToDelete = $request->input('types_of_work_delete');

            // Удаляем типы работ из базы данных
            if (!empty($typesOfWorkToDelete)) {
                // Например, используя Eloquent
                CardObjectServicesTypes::whereIn('type_work', $typesOfWorkToDelete)->delete();
            }
        }


        // --------Создаем новую запись в таблице истории---------------
        $history_card = new HistoryCardObjectMain();
        $history_card->fill($request->all());
        $history_card->card_id = $card->id;
        $history_card->save();
        // Сохранение файлов документов в таблице истории
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $content = file_get_contents($file->getRealPath()); // Получение содержимого файла
                $binaryData = new Binary($content, Binary::TYPE_GENERIC); // Создание объекта Binary с двоичными данными

                // Создаем новую запись для файла в модели HistoryCardObjectMainDoc
                $history_doc = new HistoryCardObjectMainDoc();
                $history_doc->file_content = $binaryData; // Присваиваем двоичные данные к полю file_content модели HistoryCardObjectMainDoc
                $history_doc->file_name = $file->getClientOriginalName();
                $history_doc->card_object_main_id = $history_card->id; // Связываем с исторической записью
                $history_doc->save();
            }
        }
        // Сохранение данных об обслуживаниях в таблице истории
        if ($request->has('services')) {
            $services = json_decode($request->services, true); // Преобразуем JSON в массив
            foreach ($services as $service) {
                // Создаем новую запись для обслуживания в модели HistoryCardObjectServices
                $history_newService = new HistoryCardObjectServices();
                $history_newService->fill($service); // Заполняем модель данными из массива
                $history_newService->card_object_main_id = $history_card->id;
                $history_newService->save();
                $history_serviceId = $history_newService->id;

                // Получаем данные о виде работ для текущего обслуживания
                $history_typesOfWork = $service['types_of_work'];
                foreach ($history_typesOfWork as $history_typeOfWork) {
                    HistoryCardObjectServicesTypes::create([
                        'card_id' => $history_card->id, // Связываем с исторической записью
                        'card_services_id' => $history_serviceId,
                        'type_work' => $history_typeOfWork,
                    ]);
                }
            }
        }
// Обновляем плановые даты в связанных заказах-нарядах
        $card->load('services.cardWorkOrders');
        foreach ($card->services as $service) {
            foreach ($service->cardWorkOrders as $workOrder) {
                $workOrder->planned_maintenance_date = $service->planned_maintenance_date;
                $workOrder->save();
            }
        }

        // Возвращаем успешный ответ или редирект на страницу карточки объекта
        return response()->json(['success' => 'Данные карточки объекта успешно обновлены'], 200);
    }

    private function updateCuratorInGraphs($cardId, $curator)
    {
        $graphs = CardGraph::where('cards_ids', 'like', '%"'.$cardId.'"%')->get();
        foreach ($graphs as $graph) {
            $graph->curator = $curator;
            $graph->save();
        }
    }

    //------------------ УДАЛЕНИЕ ОБСЛУЖИВАНИЯ У КАРТЧОЧКИ ------------------
    public function deleteService($cardId, $serviceId)
    {
        // Находим обслуживание по идентификатору
        $service = CardObjectServices::find($serviceId);
        // Проверяем, найдено ли обслуживание
        if (!$service) {
            return response()->json(['error' => 'Обслуживание не найдено'], 404);
        }
        // Удаляем обслуживание
        $service->delete();
        // Удаление связанных записей из card_object_service_types
        CardObjectServicesTypes::where('card_services_id', $serviceId)->delete();
        // Возвращаем успешный ответ
        return response()->json(['success' => 'Обслуживание успешно удалено'], 200);
    }

    //------------------ ПОЛЯ ВЫБОРА В ВИДАХ РАБОТЫ -> КАРТЧОКА ОБЪЕКТА ОБСЛУЖИВАНИЕ ------------------
    public function updateChecked(Request $request)
    {
        $typeId = $request->type_id;
        $checked = $request->checked;

        $type = CardObjectServicesTypes::find($typeId);
        if (!$type) {
            return response()->json(['error' => 'Вид работы не найден'], 404);
        }

        $type->checked = $checked;
        $type->save();

        return response()->json(['success' => 'Поле checked успешно обновлено'], 200);
    }

}
