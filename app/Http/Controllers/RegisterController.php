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

      //  $username = $request->input('name'); // LDAP username
        $username = $request->input('hidden_name'); // LDAP username
        $password = $request->input('password');

        if (!$this->validatePassword($username, $password)) {
            return redirect()->back()->withErrors(['password' => 'Неверный пароль'])->withInput();
        }

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
            // 'name' => $data['name'], // LDAP username
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
                'fio' => $user->getDisplayName(), // Используем DisplayName как username
                'username' => $user->getAccountName(),
            ];
        }

        return response()->json($userList);
    }

    protected function validatePassword($username, $password)
    {
        try {
            return Adldap::auth()->attempt($username, $password);
        } catch (\Adldap\Auth\UsernameRequiredException $e) {
            return false; // Пользователь не указал имя
        } catch (\Adldap\Auth\PasswordRequiredException $e) {
            return false; // Пользователь не указал пароль
        }
    }
}
