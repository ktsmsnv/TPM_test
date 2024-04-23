<?php

namespace App\Http\Controllers;

use App\Models\CardGraph;
use App\Models\CardObjectServices;
use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\CardObjectMain;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $objects = CardObjectMain::with('services')->get();
        $breadcrumbs = Breadcrumbs::generate('home');
        return view('home', compact('breadcrumbs', 'objects'));
    }


    public function profile()
    {
        // Генерация хлебных крошек
        $breadcrumbs = Breadcrumbs::generate('profile');

        // Возвращение представления с передачей хлебных крошек
        return view('profile', compact('breadcrumbs'));
    }

// Метод для обновления данных пользователя
    public function updateProfile(Request $request)
    {
        // Проверка входящих данных
//        $request->validate([
//            'name' => 'required|string|max:255',
//            'email' => 'required|string|email|max:255|unique:users,email,'.auth()->user()->id,
//        ]);

        // Обновление данных пользователя в MongoDB
        $user = Auth::user();
        DB::collection('users')->where('_id', $user->_id)->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        return redirect()->back()->with('success', 'Данные профиля успешно обновлены.');
    }

// Метод для смены пароля пользователя
    public function changePassword(Request $request)
    {
        // Проверка входящих данных
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Обновление пароля пользователя в MongoDB
        $user = Auth::user();
        DB::collection('users')->where('_id', $user->_id)->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->back()->with('success', 'Пароль успешно изменен.');
    }

    public function reestrGraphView( Request $request)
    {
        // Генерация хлебных крошек
//        $breadcrumbs = Breadcrumbs::generate('reestr-graphs');
        $objects = CardGraph::all();
        $selectedObjectMain = CardObjectMain::all();
        $selectedObjectServices = CardObjectServices::all();
//        dd($selectedObjectMain);

        // Возвращение представления с передачей хлебных крошек
        return view('reestrs/reestrGraph', compact('objects','selectedObjectMain','selectedObjectServices'));
    }

    public function reestrWorkOrdersView()
    {
        // Генерация хлебных крошек
        $breadcrumbs = Breadcrumbs::generate('reestr-work-orders');

        // Возвращение представления с передачей хлебных крошек
        return view('reestrs/reestr-work-orders', compact('breadcrumbs'));
    }
}
