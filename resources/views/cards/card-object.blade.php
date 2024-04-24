{{--страница карточка объекта --}}
@extends('layouts.app')

@section('content')
    <div class="container custom_tab_style1_outer">
        <div class="row">
            {{-- ЗАГОЛОВОК С ПАНЕЛЬЮ КНОПОК --}}
            <div class="col-md-12 text-left">
                <h1 class="mb-4"><strong>Карточка
                        объекта: "{{ $data_CardObjectMain->name ?? 'Название объекта не найдено' }}"</strong></h1>
            </div>
            <div class="btns d-flex mb-5">
                <div class="d-flex gap-2">
                    <a href="/home" type="button" class="btn btn-secondary me-5">Закрыть</a>
                    <a type="button" class="btn btn-primary btn-primary--2 copy_cardObject">Скопировать карточку объекта</a>
                    <a href="{{ route('cardObject-edit', ['id' => $data_CardObjectMain->_id]) }}" target="_blank" type="button" class="btn btn-outline-danger">Редактировать</a>
                </div>
            </div>

            {{-- КАРТОЧКА С ВКЛАДКАМИ --}}
            <ul class="nav nav-tabs custom_tab_style1" id="cardObjectTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="main-tab" data-bs-toggle="tab" data-bs-target="#main"
                            type="button" role="tab" aria-controls="main" aria-selected="true">ОСНОВНАЯ
                    </button>
                </li>
                @foreach ($data_CardObjectMain->services as $key => $service)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if ($key === 0) @endif" id="service_{{ $key + 1 }}-tab" data-bs-toggle="tab"
                                data-bs-target="#service_{{ $key + 1 }}" type="button" role="tab" aria-controls="service_{{ $key + 1 }}"
                                aria-selected="{{ $key === 0 ? 'true' : 'false' }}">Обслуживание {{ $key + 1 }}</button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content" id="cardObjectTabContent">
                {{-- ВКЛАДКА "ОСНОВНАЯ" --}}
                <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
                    <div id="main__blocks" class="d-grid">
                        {{-- ОБЩИЕ ДАННЫЕ --}}
                        <div class="member_card_style general">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Общие данные</h4>
                                    <div class="tooltip-wrapper" data-toggle="tooltip"
                                         title="для создания нажмите кнопку РЕДАКТИРОВАТЬ">
                                        <button class="btn btn-primary" disabled>Создать обслуживание</button>
                                    </div>
                                </div>
                                <div class="member-info--inputs d-flex gap-5">
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Вид инфраструктуры</label>
                                            <input name="" class="form-control w-100" readonly
                                                   value="{{ $data_CardObjectMain->infrastructure ?? 'нет данных' }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Наименование объекта</label>
                                            <input name="" class="form-control w-100" readonly
                                                   value="{{ $data_CardObjectMain->name ?? 'нет данных' }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Инв./заводской №</label>
                                            <input class="form-control w-100" name="" readonly
                                                   value="{{ $data_CardObjectMain->number ?? 'нет данных' }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Место установки</label>
                                            <input class="form-control  w-100" name="" readonly
                                                   value="{{ $data_CardObjectMain->location ?? 'нет данных' }}">
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата прихода</label>
                                            <input class="form-control w-100" name="" readonly
                                                   value="{{ isset($data_CardObjectMain->date_arrival) ? date('d-m-Y', strtotime($data_CardObjectMain->date_arrival)) : 'нет данных' }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата ввода в эксплуатацию</label>
                                            <input class="form-control w-100" name="" readonly
                                                   value="{{ isset($data_CardObjectMain->date_usage) ? date('d-m-Y', strtotime($data_CardObjectMain->date_usage)) : 'нет данных' }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата окончания аттестации/гарантии</label>
                                            <input class="form-control w-100" name="" readonly
                                                   value="{{ isset($data_CardObjectMain->date_cert_end) ?  date('d-m-Y', strtotime($data_CardObjectMain->date_cert_end)) : 'нет данных' }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата вывода из эксплуатации</label>
                                            <input class="form-control  w-100" name="" readonly
                                                   value="{{ isset($data_CardObjectMain->date_usage_end) ? date('d-m-Y', strtotime($data_CardObjectMain->date_usage_end)) : 'нет данных' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ДОКУМЕНТАЦИЯ --}}
                        <div class="member_card_style documentation">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Документация</h4>
                                    <div class="tooltip-wrapper" data-toggle="tooltip"
                                         title="для вложения нажмите кнопку РЕДАКТИРОВАТЬ">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#docDownloadModal" disabled>Вложить документ
                                        </button>
                                    </div>
                                </div>
                                <div class="objectDocs">
                                    <ul>
                                        @if ($data_CardObjectMainDocs !== null)
                                            @foreach ($data_CardObjectMainDocs as $file)
                                                <li>
                                                    <a href="{{ route('downloadDocument', $file->id) }}">{{ $file->file_name }}</a>
                                                </li>
                                            @endforeach
                                        @else
                                            <p>Нет доступных документов</p>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        {{-- ИЗОБРАЖЕНИЕ --}}
                        <div class="member_card_style image">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Изображение объекта</h4>
                                    <div class="tooltip-wrapper" data-toggle="tooltip"
                                         title="для загрузки нажмите кнопку РЕДАКТИРОВАТЬ">
                                        <button class="btn btn-primary disabled" data-bs-toggle="modal"
                                                data-bs-target="#imageDownloadModal">Загрузить
                                        </button>
                                    </div>
                                </div>
                                <div class="objectImage">
                                    @if ($data_CardObjectMain)
                                        <img src="{{ route('getImage', ['id' => $data_CardObjectMain->id]) }}"
                                             alt="Image">
                                    @else
                                        <p>Нет доступных изображений</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ВКЛАДКА "ОБСЛУЖИВАНИЕ" --}}
                @foreach ($data_CardObjectMain->services as $key => $service)
                    <div class="tab-pane fade @if ($key === 0) @endif" id="service_{{ $key + 1 }}" role="tabpanel"
                         aria-labelledby="service_{{ $key + 1 }}-tab">
                        <div id="service__blocks" class="d-grid">
                        {{-- ОБСЛУЖИВАНИЕ ТРМ --}}
                        <div class="member_card_style services">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Обслуживание ТРМ {{ $key + 1 }}</h4>
                                    <button class="btn btn-primary">Обновить даты</button>
                                    <div data-toggle="tooltip"
                                         title="для изменения нажмите кнопку РЕДАКТИРОВАТЬ">
                                        <input type="checkbox" class="form-check-input me-1" id="disableInTable_{{ $key + 1 }}"
                                               @if ($service->checked) checked @endif disabled>
                                        <label class="form-check-label disableInTable" for="disableInTable">Не
                                            выводить
                                            на основной
                                            экран, в график TPM и не отправлять уведомления</label>
                                    </div>
                                </div>
                                <div class="member-info--inputs d-flex gap-5">
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100" >Вид обслуживания</label>
                                            <input name="" class="form-control w-100" value="{{ $service->service_type }}" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Сокращенное название</label>
                                            <input name="" class="form-control w-100" value="{{ $service->short_name }}" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Исполнитель</label>
                                            <input class="form-control w-100" name="" value="{{ $service->performer }}" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Ответственный</label>
                                            <input class="form-control  w-100" name="" value="{{ $service->responsible }}" readonly>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Периодичность</label>
                                            <input class="form-control w-100" name="" value="{{ $service->frequency }}" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата предыдущего обслуживания</label>
                                            <input class="form-control w-100" name="" value="{{ date('d-m-Y', strtotime($service->prev_maintenance_date)) }}" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Плановая дата обслуживания</label>
                                            <input class="form-control w-100" name="" value="{{ date('d-m-Y', strtotime($service->planned_maintenance_date)) }}" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Цвет в календаре</label>
                                            <div class="color-options">
                                                <div class="color-option" style="background-color: {{ $service->calendar_color }}"></div>
                                            </div>
                                            <input type="hidden" id="selectedColor" name="selectedColor" value="{{ $service->calendar_color }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ВИДЫ РАБОТ --}}
                        <div class="member_card_style types">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Виды работ</h4>
                                    <div class="tooltip-wrapper" data-toggle="tooltip"
                                         title="для добавления нажмите кнопку РЕДАКТИРОВАТЬ">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#typesModal" disabled>Добавить вид работ
                                        </button>
                                    </div>
                                </div>
                                <div class="typesOfWork">
                                    <!-- Используем класс row для создания строки -->
                                    <div class="grid-container">
                                        @foreach ($service->services_types as $type)
                                            <div class="grid-item">
                                                <div class="form-check d-flex align-items-center gap-2">
                                                    <input type="checkbox" class="form-check-input type-checkbox"
                                                           id="type_{{ $type->id }}" data-id="{{ $type->id }}"
                                                        {{ $type->checked ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="type_{{ $type->id }}">
                                                        {{ $type->type_work }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <script>
                                        $('.type-checkbox').on('change', function () {
                                            var typeId = $(this).data('id');
                                            var isChecked = $(this).is(':checked');

                                            $.ajax({
                                                type: "POST",
                                                headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                },
                                                url: "{{ route('update-type-checked') }}",
                                                data: {
                                                    type_id: typeId,
                                                    checked: isChecked ? 1 : 0 // Преобразуем в целое число для сохранения в MongoDB
                                                },
                                                success: function (response) {
                                                    console.log(response);
                                                },
                                                error: function (error) {
                                                    console.log(error);
                                                }
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                        {{-- РАСХОДНЫЕ МАТЕРИАЛЫ --}}
                        <div class="member_card_style materials">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Расходные материалы и ЗИП</h4>
                                </div>
                                <div class="material_text w-100">
                                    <!-- Добавляем textarea с атрибутом placeholder -->
                                    <textarea class="form-control" readonly data-toggle="tooltip"
                                              title="для изменения нажмите кнопку РЕДАКТИРОВАТЬ">{{ $service->consumable_materials }}</textarea>
                                </div>
                            </div>
                        </div>
                        {{-- ИЗОБРАЖЕНИЕ --}}
                        <div class="member_card_style image">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Изображение объекта</h4>
                                    <div class="tooltip-wrapper" data-toggle="tooltip"
                                         title="для загрузки нажмите кнопку РЕДАКТИРОВАТЬ">
                                        <button class="btn btn-primary disabled" data-bs-toggle="modal"
                                                data-bs-target="#imageDownloadModal">Загрузить
                                        </button>
                                    </div>
                                </div>
                                <div class="objectImage">
                                    @if ($data_CardObjectMain)
                                        <img src="{{ route('getImage', ['id' => $data_CardObjectMain->id]) }}"
                                             alt="Image">
                                    @else
                                        <p>Нет доступных изображений</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            // Обработчик клика на кнопку "Скопировать карточку объекта"
            $('.copy_cardObject').click(function () {
                // Отправляем AJAX-запрос на сервер для создания копии карточки объекта
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('copy-cardObject') }}",
                    data: {id: "{{ $data_CardObjectMain->_id }}"},
                    success: function (response) {
                        window.open(response.url, '_blank');
                    },
                    error: function (error) {
                        // В случае ошибки можно вывести сообщение об ошибке или выполнить другие действия
                        console.log(error);
                    }
                });
            });
        });
    </script>
@endsection
