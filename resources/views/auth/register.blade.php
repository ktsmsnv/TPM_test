@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Регистрация') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="ldap-user" class="col-md-4 col-form-label text-md-end">{{ __('Выберите пользователя') }}</label>
                                <div class="col-md-6">
                                    <select id="ldap-user" class="form-select" name="ldap_user" required autofocus>
                                        <option value="">Выберите пользователя</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('ФИО') }}</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="role" class="col-md-4 col-form-label text-md-end">{{ __('Роль') }}</label>
                                <div class="col-md-6">
                                    <select id="role" class="form-select" name="role" required autocomplete="role" autofocus>
                                        <option value="responsible">Ответственный за обслуживание</option>
                                        <option value="executor">Исполнитель обслуживания</option>
                                        <option value="curator">Куратор</option>
                                        <option value="admin">Администратор</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Пароль') }}</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Подтвердите пароль') }}</label>
                                <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Создать профиль') }}
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <a href="{{ route('profile') }}">НАЗАД</a>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('/ldap-users')
                .then(response => response.json())
                .then(data => {
                    clusterUsers(data);
                });

            document.getElementById('ldap-user').addEventListener('change', function () {
                const selectedUser = this.options[this.selectedIndex];
                const email = selectedUser.value;
                const username = selectedUser.dataset.username;
                const password = selectedUser.dataset.password;

                document.getElementById('name').value = username;
                document.getElementById('email').value = email;
                document.getElementById('password').value = password;
                document.getElementById('password-confirm').value = password;
            });
        });

        function clusterUsers(data) {
            const ldapUserSelect = document.getElementById('ldap-user');
            data.forEach(user => {
                let option = document.createElement('option');
                option.value = user.email;
                option.text = user.email;
                option.dataset.username = user.username;
                option.dataset.password = user.password;
                ldapUserSelect.appendChild(option);
            });
        }
    </script>
@endsection
