<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use App\Models\CardObjectMain;
use App\Models\CardObjectMainDoc;
use Illuminate\Support\Facades\Storage;
use Mongodb\Laravel\Connection;

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
        return view('cards/card-object', compact('data_CardObjectMain'));
    }


    // СОЗДАНИЕ карточки объекта
    public function create() {
        $breadcrumbs = Breadcrumbs::generate('card-object-create');
        return view('cards/card-object-create', compact('breadcrumbs'));
    }

    // РЕДАКТИРОВАНИЕ карточки объекта
    public function edit() {
        $breadcrumbs = Breadcrumbs::generate('/card-object/edit');
        return view('cards/card-object-edit', compact('breadcrumbs'));
    }

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

        // Получаем экземпляр GridFS
//        $gridFS = app(Connection::class)->getGridFS();

        // Обработка сохранения загруженных изображений
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Сохранение изображений в MongoDB GridFS
                $imageContent = file_get_contents($image->getRealPath());
                $imageName = $image->getClientOriginalName();
        //        $gridFS->storeBytes($imageContent, ['filename' => $imageName]);
                // Предполагается, что images - это коллекция для сохранения изображений
                // Дополнительно можно сохранить ссылку на изображение в модели $card
                $card->image = $imageName;
            }
        }

        // Обработка сохранения загруженных файлов
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Сохранение файлов в MongoDB GridFS
                $fileContent = file_get_contents($file->getRealPath());
                $fileName = $file->getClientOriginalName();
         //       $gridFS->storeBytes($fileContent, ['filename' => $fileName]);
                // Создание модели для сохранения метаданных о файле (необязательно)
                $fileModel = new CardObjectMainDoc();
                $fileModel->name = $fileName;
                $fileModel->card_id = $card->id; // Привязка к ID основной карточки
                $fileModel->save();
            }
        }

        // Возвращаем ответ об успешном сохранении данных
        return response()->json(['message' => 'Данные успешно сохранены'], 200);
    }

}
