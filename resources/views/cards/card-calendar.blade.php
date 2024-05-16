{{--страница карточка календаря --}}
@extends('layouts.app')

@section('content')
    <div class="container custom_tab_style1_outer">
        <div class="row">
            {{-- ЗАГОЛОВОК С ПАНЕЛЬЮ КНОПОК --}}
            <div class="col-md-12 text-left">
                <h2 class="mb-4"><strong>Карточка календаря для объекта {{ $cardObjectMain->name }}</strong></h2>
            </div>
            <input type="hidden" name="card_id" value="{{ $cardObjectMain->id }}">
            <div class="btns d-flex mb-5">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-danger editCard">Редактировать</button>
                    <a href="/pageReestrCalendar" type="button" class="btn btn-secondary me-5">Назад</a>

                    <button type="button" class="btn btn-success">Выгрузить PDF</button>
                    <a href="/home/card-object/{{$cardObjectMain->id}}" target="_blank" type="button" class="btn btn-primary me-5">Открыть карточку объекта</a>
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
                    <div id="main__blocks_calendar" class="d-grid">
                        {{-- ОБЩИЕ ДАННЫЕ --}}
                        <div class="member_card_style general">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Общие данные</h4>
                                    <button class="btn btn-primary archive_calendar {{ $cardCalendar->date_archive != null ? 'disabled' : '' }}">
                                        Заархивировать
                                    </button>
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
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Куратор</label>
                                            <input class="form-control  w-100" name="curator" value="{{ $cardObjectMain->curator }}" readonly  data-toggle="tooltip" title="изменить можно в карточке объекта 'основная'">
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
                                            <input type="number" name="year" value="{{ $cardCalendar -> year }}" class="form-control w-100" readonly
                                                   data-toggle="tooltip" title="изменить можно нажав кнопку редактировать">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата создания</label>
                                            <input type="date" name="date_create" class="form-control w-100" value="{{ $cardCalendar -> date_create }}" readonly
                                                   data-toggle="tooltip" title="дата создания календаря">
                                        </div>
                                        {{--                                        <div class="d-flex justify-content-between align-items-center gap-3">--}}
                                        {{--                                            <label class="w-100">Дата последнего сохранения</label>--}}
                                        {{--                                            <input class="form-control w-100" name=""--}}
                                        {{--                                                   placeholder="Введите дату последнего сохранения">--}}
                                        {{--                                        </div>--}}
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата архивации</label>
                                            @if ($cardCalendar && $cardCalendar->date_archive !== null)
                                                <input name="date_archive" class="form-control w-100" value="{{ $cardCalendar->date_archive }}" readonly
                                                       data-toggle="tooltip" aria-label="дата архивации" title="дата архивации">
                                            @else
                                                <input name="date_archive" class="form-control w-100" value="дата архивации"
                                                       readonly style="opacity: 0.5;" data-toggle="tooltip" aria-label="дата архивации" title="дата архивации">
                                            @endif
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
                                    <div class="member-info--inputs d-flex flex-column gap-3">
                                        <div class="d-flex justify-content-between gap-3">
                                            <div class="d-flex align-items-center gap-0">
                                                <label class="w-100">Вид обслуживания {{ $index + 1 }}</label>
                                                <input name="services[{{ $index }}][service_type]" value="{{ $service->service_type }}" class="form-control w-100" readonly
                                                       data-toggle="tooltip" title="{{ $service->service_type }}">
                                            </div>
                                            <div class="d-flex align-items-center gap-0">
                                                <label class="w-100">Периодичность</label>
                                                <input name="services[{{ $index }}][frequency]" value="{{ $service->frequency }}"  class="form-control w-100" readonly
                                                       data-toggle="tooltip" title="{{ $service->frequency }}">
                                            </div>
                                            <div class="d-flex align-items-center gap-0">
                                                <label class="w-100">Исполнитель</label>
                                                <input name="services[{{ $index }}][performer]" value="{{ $service->performer }}"  class="form-control w-100" readonly
                                                 data-toggle="tooltip" title="{{ $service->performer }}">
                                            </div>
                                            <div class="d-flex align-items-center gap-0">
                                                <label class="w-100">Ответственный</label>
                                                <input name="services[{{ $index }}][responsible]" value="{{ $service->responsible }}"  class="form-control w-100" readonly
                                                 data-toggle="tooltip" title="{{ $service->responsible }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        {{-- ИЗОБРАЖЕНИЕ --}}
                        <div class="member_card_style image">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Изображение объекта</h4>
                                </div>
                                <div class="objectImage">
                                    @if ($cardObjectMain && $cardObjectMain->image)
                                        <!-- Если у объекта есть изображение, отобразите его -->
                                        <img src="{{ route('getImage', ['id' => $cardObjectMain->id]) }}" alt="Image">
                                    @else
                                        <!-- Если у объекта нет изображения, отобразите сообщение -->
                                        <p>Нет доступных изображений</p>
                                    @endif
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
                                    @if ($cardObjectMain && $cardObjectMain->image)
                                        <!-- Если у объекта есть изображение, отобразите его -->
                                        <img src="{{ route('getImage', ['id' => $cardObjectMain->id]) }}" alt="Image">
                                    @else
                                        <!-- Если у объекта нет изображения, отобразите сообщение -->
                                        <p>Нет доступных изображений</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Модальное окно подтверждения завершения заказа-наряда -->
        <div class="modal fade" id="confirmArchiveModal" tabindex="-1" aria-labelledby="confirmArchiveModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmArchiveModalLabel">Подтверждение архивации календаря</h5>
                        <button type="button" class="btn-close" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Вы уверены, что хотите архивировать данный календарь объекта
                       "{{$cardObjectMain->name}}" ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="button" class="btn btn-danger" id="confirmArchiveButton">Заархивировать</button>
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


                // Обработчик события нажатия на кнопку "Завершить заказ"
                $('.archive_calendar').click(function () {
                    // Открываем модальное окно с вопросом о завершении заказа-наряда
                    $('#confirmArchiveModal').modal('show');
                });
                // Обработчик события нажатия на кнопку "Да" в модальном окне подтверждения
                $('#confirmArchiveButton').click(function () {
                    // Устанавливаем текущую дату в поле "Фактическая дата"
                    const currentDate = new Date();
                    const formattedDate = currentDate.toLocaleDateString('ru-RU').split('.').reverse().join('-'); // Форматируем дату в формат dd-mm-yyyy
                    $('input[name="date_archive"]').val(formattedDate);
                    // Отправляем данные в контроллер для сохранения изменений в базе данных
                    $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('archiveCalendar') }}",
                        data: {
                            id: "{{ $cardCalendar->_id }}", // Здесь нужно передать ID текущего заказа-наряда
                            date_archive: formattedDate, // Передаем текущую дату
                        },
                        success: function (response) {
                            // Обработка успешного завершения запроса
                            console.log(response);
                        },
                        error: function (error) {
                            // Обработка ошибки
                            console.log(error);
                        }
                    });
                    // Закрываем модальное окно подтверждения
                    $('#confirmArchiveModal').modal('hide');
                });
            });
        </script>
@endsection
