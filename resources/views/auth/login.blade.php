<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link type="image/x-icon" rel="shortcut icon" href="{{ URL::asset('storage/favicon.ico') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TRM') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="login__page">
<div id="app">
    <div class="login__page-block d-flex align-items-center justify-content-center">
        <div class="col-md-3">
            <div class="card mb-5">
                <div class="login__page-logo pb-3"><img src="{{ asset('/storage/login.png') }}"></div>
                <h3 class="login__page-header text-center pt-5 pb-2">{{ __('Вход') }}</h3>
                <div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row mb-3">
                            <label for="email"
                                   class="col-md-4 col-form-label text-md-end">{{ __('Логин') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror" name="email"
                                       value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password"
                                   class="col-md-4 col-form-label text-md-end">{{ __('Пароль') }}</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
                                    <span data-toggle="tooltip" title="Скрыть пароль" class="input-group-btn" id="eyeSlash" style="display: none;">
                                            <button class="btn btn-default reveal" onclick="visibility3()" type="button"><img src="{{ asset('/storage/eye.svg') }}"></button>
                                        </span>
                                    <span data-toggle="tooltip" title="Показать пароль" class="input-group-btn" id="eyeShow">
                                            <button class="btn btn-default reveal" onclick="visibility3()" type="button"><img src="{{ asset('/storage/eye-slash.svg') }}"></button>
                                        </span>
                                </div>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Запомнить меня') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Вход') }}
                                </button>

                                {{-- @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('Забыли пароль?') }}
                                            </a>
                                        @endif --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <a href="{{ route('register') }}">зарегистрироваться</a>
        </div>
    </div>
</div>
<script>
    function visibility3() {
        var x = document.getElementById('password');
        var eyeShow = document.getElementById('eyeShow');
        var eyeSlash = document.getElementById('eyeSlash');

        if (x.type === 'password') {
            x.type = "text";
            eyeShow.style.display = 'none';
            eyeSlash.style.display = 'inline-block';
        } else {
            x.type = "password";
            eyeShow.style.display = 'inline-block';
            eyeSlash.style.display = 'none';
        }
    }
</script>
</body>
</html>
