{{--страница личный кабинет --}}
@extends('layouts.app')

@section('content')
    <div class="profile container mt-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card p-3 py-4">
                    <div class="text-center">
                        {{-- <img src="{{ asset('/storage/user.png') }}" width="100" class="rounded-circle" alt="user">--}}
                        <svg id="Layer_1" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"
                             xmlns:xlink="http://www.w3.org/1999/xlink">
                            <linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="0" x2="112" y1="256"
                                            y2="256">
                                <stop offset="0" stop-color="#33a8d9"/>
                                <stop offset="1" stop-color="#4273b8"/>
                            </linearGradient>
                            <path
                                d="m512 256c0-141.159-114.841-256-256-256s-256 114.841-256 256c0 139.212 111.697 252.818 250.176 255.926 1.938.049 3.879.074 5.824.074s3.886-.025 5.823-.074c138.48-3.108 250.177-116.714 250.177-255.926zm-480 0c0-123.514 100.486-224 224-224s224 100.486 224 224c0 60.333-23.985 115.163-62.911 155.479-8.997-31.029-26.903-59.063-51.679-80.411-14.827-12.775-31.673-22.741-49.699-29.607 25.813-18.603 42.651-48.916 42.651-83.091 0-56.443-45.92-102.363-102.362-102.363-56.443 0-102.363 45.92-102.363 102.363 0 34.175 16.839 64.489 42.651 83.091-18.026 6.866-34.872 16.832-49.699 29.607-24.776 21.348-42.682 49.381-51.679 80.41-38.925-40.316-62.91-95.145-62.91-155.478zm224 224c-1.634 0-3.262-.027-4.887-.063-47.651-1.156-92.503-19.21-127.601-51.296 13.633-61.999 68.1-106.179 132.488-106.179 64.387 0 118.854 44.18 132.487 106.179-35.101 32.086-79.953 50.14-127.6 51.296-1.626.036-3.253.063-4.887.063zm0-191.268c-38.798 0-70.363-31.564-70.363-70.362s31.565-70.363 70.363-70.363 70.362 31.565 70.362 70.363-31.564 70.362-70.362 70.362z"
                                fill="url(#SVGID_1_)"/>
                        </svg>
                    </div>
                    <div class="text-center mt-3">
                        <span class="fonts bg-secondary p-1 px-4 rounded text-white">
                            Роль:
                            @if(Auth::user()->role == 'responsible')
                                Ответственный за обслуживание
                            @elseif(Auth::user()->role == 'executor')
                                Исполнитель обслуживания
                            @elseif(Auth::user()->role == 'curator')
                                Куратор
                            @elseif(Auth::user()->role == 'administrator')
                                Администратор
                            @else
                                Неопределено
                            @endif
                        </span>
                        <h5 class="mt-4 mb-4 text-start"><strong class="me-4">Фамилия
                                Имя:</strong> {{ Auth::user()->name }}</h5>
                        <h5 class="mt-2 mb-5 text-start"><strong class="me-4">Почта:</strong> {{ Auth::user()->email }}
                        </h5>
                        <div class="buttons d-flex gap-3 justify-content-center">
                            <div class="buttons d-flex gap-3 justify-content-center">
                                <a href="" class="btn btn-outline-primary px-4">Подключить telegram бота</a>
                                <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#editProfileModal">Изменить данные</button>
                                <button type="button" class="btn btn-secondary px-4" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Сменить пароль</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Редактирование профиля</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Фамилия Имя</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ auth()->user()->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email адрес</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="{{ auth()->user()->email }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Смена пароля</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('profile.change-password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="password" class="form-label">Новый пароль</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Сохранить пароль</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
