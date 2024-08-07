<?php

namespace App\Http\Controllers;

use Adldap\Laravel\Facades\Adldap;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        return redirect()->route('profile')->with('success', 'Пользователь успешно зарегистрирован');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'], // LDAP username
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], // LDAP email
            'password' => ['required', 'string', 'min:1', 'confirmed'], // LDAP password
            'role' => ['required', 'string', 'in:responsible,executor,curator,admin'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'], // LDAP username
            'email' => $data['email'], // LDAP email
            'password' => Hash::make($data['password']), // Зашифрованный пароль
            'role' => $data['role'],
        ]);
    }

    public function getLdapUsers()
    {
        $users = Adldap::search()->users()->get();

        $userList = [];
        foreach ($users as $user) {
            $userList[] = [
                'email' => $user->getEmail(),
                'username' => $user->getDisplayName(), // Добавляем username из LDAP
            //    'password' =>  $user->getPassword(),
            ];
        }

        return response()->json($userList);
    }
}
