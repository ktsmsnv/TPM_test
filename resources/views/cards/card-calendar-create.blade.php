{{--страница карточка календаря --}}
@extends('layouts.app')

@section('content')
    <div class="container custom_tab_style1_outer">
        <div class="row">
            {{-- ЗАГОЛОВОК С ПАНЕЛЬЮ КНОПОК --}}
            <div class="col-md-12 text-left">
                <h2 class="mb-4"><strong>Создание картчоки календаря для объекта {{ $cardObjectMain->name }}</strong></h2>
            </div>
            <div class="btns d-flex mb-5">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success">Сохранить</button>
                    <button type="button" class="btn btn-secondary me-5">Закрыть</button>

                    <button type="button" class="btn btn-success">Выгрузить PDF</button>
                    <button type="button" class="btn btn-success">Открыть карточку объекта</button>
                </div>
            </div>

            {{-- КАРТОЧКА С ВКЛАДКАМИ --}}
            <ul class="nav nav-tabs custom_tab_style1" id="cardCalendarTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="main-tab" data-bs-toggle="tab" data-bs-target="#main"
                            type="button" role="tab" aria-controls="main" aria-selected="true">ОСНОВНАЯ
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="service_1-tab" data-bs-toggle="tab" data-bs-target="#service_1"
                            type="button" role="tab" aria-controls="service_1" aria-selected="false">КАЛЕНДАРЬ
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="cardCalendarTabContent">
                {{-- ВКЛАДКА "ОСНОВНАЯ" --}}
                <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
                    <div id="main__blocks" class="d-grid">
                        {{-- ОБЩИЕ ДАННЫЕ --}}
                        <div class="member_card_style general">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Общие данные</h4>
                                    <button class="btn btn-primary">Заархивировать</button>
                                </div>
                                <div class="member-info--inputs d-flex gap-5">
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Вид инфраструктуры</label>
                                            <input name="infrastructure" value="{{ $cardObjectMain->infrastructure }}" placeholder="Введите вид инфраструктуры" class="form-control w-100" readonly
                                                   data-toggle="tooltip" title="изменить можно в карточке объекта 'основная'">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Наименование объекта</label>
                                            <input name="name" value="{{ $cardObjectMain->name }}" placeholder="Введите наименование объекта" class="form-control w-100" readonly
                                                   data-toggle="tooltip" title="изменить можно в карточке объекта 'основная'">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Инв./заводской №</label>
                                            <input class="form-control w-100" name="number" value="{{ $cardObjectMain->number }}" readonly  data-toggle="tooltip" title="изменить можно в карточке объекта 'основная'"
                                                   placeholder="Введите инв./заводской №">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Место установки</label>
                                            <input class="form-control  w-100" name="location" value="{{ $cardObjectMain->location }}" readonly  data-toggle="tooltip" title="изменить можно в карточке объекта 'основная'"
                                                   placeholder="Введите место установки">
                                        </div>
{{--                                        <div class="d-flex justify-content-between align-items-center gap-3">--}}
{{--                                            <label class="w-100">Куратор</label>--}}
{{--                                            <input class="form-control  w-100" name=""--}}
{{--                                                   placeholder="Введите куратора">--}}
{{--                                        </div>--}}
                                    </div>
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Год действия</label>
                                            <input type="number" name="year" value="{{ date('Y') }}" min="{{ date('Y') }}" max="{{ date('Y') + 10 }}" class="form-control w-100" placeholder="Введите год действия">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата создания</label>
                                            <input type="date" name="date_create" value="{{ date('Y-m-d') }}" class="form-control w-100" placeholder="Введите дату создания" readonly>
                                        </div>
{{--                                        <div class="d-flex justify-content-between align-items-center gap-3">--}}
{{--                                            <label class="w-100">Дата последнего сохранения</label>--}}
{{--                                            <input class="form-control w-100" name=""--}}
{{--                                                   placeholder="Введите дату последнего сохранения">--}}
{{--                                        </div>--}}
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата архивации</label>
                                            <input class="form-control  w-100" name=""
                                                   placeholder="дата архивации" readonly style="opacity: 0.5;" data-toggle="tooltip" title="дата появится после завершения заказ-наряда">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ОБСЛУЖИВАНИЕ ТРМ --}}
                        <div class="member_card_style documentation">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Обслуживание ТРМ</h4>
                                </div>
                                @foreach($cardObjectMain->services as $index => $service)
                                    <div class="member-info--inputs d-flex gap-5">
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex justify-content-between align-items-center gap-3">
                                                <label class="w-100">Вид обслуживания {{ $index + 1 }}</label>
                                                <input name="services[{{ $index }}][service_type]" value="{{ $service->service_type }}" class="form-control w-100" readonly>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center gap-3">
                                                <label class="w-100">Периодичность</label>
                                                <input name="services[{{ $index }}][frequency]" value="{{ $service->frequency }}"  class="form-control w-100" readonly>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center gap-3">
                                                <label class="w-100">Исполнитель</label>
                                                <input name="services[{{ $index }}][performer]" value="{{ $service->performer }}"  class="form-control w-100" readonly>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center gap-3">
                                                <label class="w-100">Ответственный</label>
                                                <input name="services[{{ $index }}][responsible]" value="{{ $service->responsible }}"  class="form-control w-100" readonly>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                {{--                                <div class="member-info--inputs d-flex gap-5">--}}
{{--                                    <div class="d-flex justify-content-between">--}}
{{--                                        <div class="d-flex justify-content-between align-items-center gap-3">--}}
{{--                                            <label class="w-100">Вид обслуживания 1</label>--}}
{{--                                            <input name="" placeholder="Введите вид обслуживания 1"--}}
{{--                                                   class="form-control w-100">--}}
{{--                                        </div>--}}
{{--                                        <div class="d-flex justify-content-between align-items-center gap-3">--}}
{{--                                            <label class="w-100">Периодичность</label>--}}
{{--                                            <input class="form-control w-100" name=""--}}
{{--                                                   placeholder="Введите периодичность">--}}
{{--                                        </div>--}}
{{--                                        <div class="d-flex justify-content-between align-items-center gap-3">--}}
{{--                                            <label class="w-100">Исполнитель</label>--}}
{{--                                            <input class="form-control w-100" name=""--}}
{{--                                                   placeholder="Введите исполнителя">--}}
{{--                                        </div>--}}
{{--                                        <div class="d-flex justify-content-between align-items-center gap-3">--}}
{{--                                            <label class="w-100">Ответственный</label>--}}
{{--                                            <input class="form-control w-100" name=""--}}
{{--                                                   placeholder="Введите ответственного">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                        {{-- ИЗОБРАЖЕНИЕ --}}
                        <div class="member_card_style image">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Изображение объекта</h4>
                                </div>
                                <div class="objectImage">
                                    <img src="http://placehold.it/350x450"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ВКЛАДКА "КАЛЕНДАРЬ" --}}
                <div class="tab-pane fade" id="service_1" role="tabpanel" aria-labelledby="service_1-tab">
                    <div id="service__blocks" class="d-grid">
                        {{-- КАЛЕНДАРЬ ТРМ --}}
                        <div class="member_card_style services">
                            <div class="member-info">
                                <h4>Календарь ТРМ</h4>
                                <div class="member-info--inputs">
                                    {{-- КАЛЕНДАРЬ --}}
                                    <div id='calendar'></div>
                                </div>
                            </div>
                        </div>
                        {{-- ИЗОБРАЖЕНИЕ --}}
                        <div class="member_card_style image">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Изображение объекта</h4>
                                </div>
                                <div class="objectImage">
                                    <img src="http://placehold.it/350x450"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>

            $(document).ready(function () {
                $("#cardCalendarTab").show;

                // Получаем все блоки цветов
                const colorOptions = document.querySelectorAll('.color-option');
                // Добавляем обработчик события для каждого блока цвета
                colorOptions.forEach(option => {
                    option.addEventListener('click', () => {
                        // Убираем рамку у всех блоков цветов
                        colorOptions.forEach(opt => opt.classList.remove('selected'));
                        // Добавляем рамку только выбранному блоку цвета
                        option.classList.add('selected');
                        // Получаем цвет выбранного блока и устанавливаем его в скрытом поле ввода
                        const selectedColor = option.getAttribute('data-color');
                        document.getElementById('selectedColor').value = selectedColor;
                    });
                });

                // После загрузки страницы
                $('#service_1-tab').on('shown.bs.tab', function (e) {
                    // Если вкладка с календарем стала активной
                    if (e.target.id === 'service_1-tab') {
                        // Инициализируем календарь
                        var calendarEl = document.getElementById('calendar');
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            timeZone: 'UTC',
                            initialView: 'multiMonthYear',
                            locale: 'ru',
                            editable: true
                        });
                        // Рендерим календарь
                        calendar.render();
                        // Пересчитываем размеры календаря после рендеринга
                        calendar.updateSize();
                    }
                });
            });
        </script>
@endsection
