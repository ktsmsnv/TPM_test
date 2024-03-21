{{--страница личный кабинет --}}
@extends('layouts.app')

@section('content')
    <div class="profile container mt-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card p-3 py-4">
                    <div class="text-center">
                        <img src="{{ asset('/storage/user.png') }}" width="100" class="rounded-circle" alt="user">
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
                        <h5 class="mt-4 mb-4 text-start"><strong class="me-4">Фамилия Имя:</strong>  {{ Auth::user()->name }}</h5>
                        <h5 class="mt-2 mb-5 text-start"><strong class="me-4">Почта:</strong> {{ Auth::user()->email }}</h5>
                        <div class="buttons">
                            <button class="btn btn-outline-primary px-4">Подключить telegram бота</button>
                            <button class="btn btn-primary px-4 ms-3">Изменить данные</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
