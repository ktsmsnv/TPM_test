{{--страница карточка графа --}}
@extends('layouts.app')

@section('content')
    <div class="container custom_tab_style1_outer">
        <select class="form-control d-none" id="locale">
            <option value="ru-RU">ru-RU</option>
        </select>
        <div class="row">
            {{-- ЗАГОЛОВОК С ПАНЕЛЬЮ КНОПОК --}}
            <div class="col-md-12 text-left">
                <h1 class="mb-4"><strong>Карточка графика: "{{ $data_CardGraph->name ?? 'Название объекта не найдено' }}"</strong></h1>
            </div>
            <div class="btns d-flex mb-5">
                <div class="d-flex gap-2">
                    <a href="/pageReestrGraph" type="button" class="btn btn-secondary me-5">Закрыть</a>
                    <button type="button" class="btn btn-success" data-toggle="tooltip"
                            title="ДАННАЯ КНОПКА ПОКА НЕ РАБОТАЕТ">Выгрузить PDF</button>
                    <a href="{{ route('cardGraph-edit', ['id' => $data_CardGraph->_id]) }}"
                       target="_blank" type="button" class="btn btn-outline-danger">Редактировать</a>
{{--                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addObjectCardModal" id="addObjectCardButton">--}}
{{--                        Добавить карточку объекта--}}
{{--                    </button>--}}
{{--                    <button type="button" class="btn btn-primary" data-toggle="modal"--}}
{{--                            data-target="#addObjectCardModal" id="addObjectCardBtn">--}}
{{--                        Добавить карточку объекта--}}
{{--                    </button>--}}
                </div>
            </div>

            {{-- КАРТОЧКА С ВКЛАДКОЙ --}}
            <ul class="nav nav-tabs custom_tab_style1" id="cardGraphTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="mainCardGraph-tab" data-bs-toggle="tab" data-bs-target="#mainCardGraph"
                            type="button" role="tab" aria-controls="mainCardGraph" aria-selected="true">ОСНОВНАЯ
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="cardGraphTabContent">
                {{-- ВКЛАДКА "ОСНОВНАЯ" --}}
                <div class="tab-pane fade show active" id="mainCardGraph" role="tabpanel" aria-labelledby="mainCardGraph-tab">
                    <div id="main__blocks" class="d-grid">
                        {{-- ОБЩИЕ ДАННЫЕ --}}
                        <div class="member_card_style general">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Общие данные</h4>
                                    <button class="btn btn-primary" id="confirmArchiveGraph">Заархивировать</button>
                                </div>
                                <div class="member-info--inputs d-flex gap-5">
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Вид инфраструктуры</label>
                                            <input name="infrastructure_type" placeholder="Введите вид инфраструктуры" class="form-control w-100"
                                                   readonly value="{{ $data_CardGraph->infrastructure_type ?? 'нет данных' }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Куратор</label>
                                            <input name="curator" placeholder="Введите куратора" class="form-control w-100"
                                                   readonly value="{{$data_CardGraph->curator ?? 'нет данных' }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Год действия</label>
                                            <input name="year_action" placeholder="Введите год действия" class="form-control w-100"
                                                   readonly value="{{$data_CardGraph->year_action ?? 'нет данных' }}">
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата создания</label>
                                            <input name="date_create" placeholder="Введите дату создания" class="form-control w-100"
                                                   readonly value="{{ date('d.m.Y', strtotime($data_CardGraph->date_create)) ?? 'нет данных'  }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата последнего сохранения</label>
                                            <input name="date_last_save" placeholder="Введите дату последнего сохранения" class="form-control w-100"
                                                   readonly value="{{ date('d.m.Y', strtotime($data_CardGraph->date_last_save)) ?? 'нет данных'  }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата архивации</label>
                                            @if ($data_CardGraph && $data_CardGraph->date_archive)
                                                <input name="date_archive" placeholder="Введите дату архивации" class="form-control w-100"
                                                   readonly value="{{ date('d.m.Y', strtotime($data_CardGraph->date_archive)) ?? 'нет данных'  }}">
                                            @else
                                                <input name="date_archive" class="form-control w-100" value="Дата архивации"
                                                       readonly style="opacity: 0.5;" data-toggle="tooltip" title="Дата архивации появится после нажатия на кнопку Заархивировать">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ГРАФИК TPM --}}
                        <div class="member_card_style documentation">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>График ТРМ</h4>
                                </div>
                                <div class="objects">
                                    <table id="reestrCardGraph"
                                           data-toolbar="#toolbar"
                                           data-search="true"
                                           data-show-refresh="true"
                                           data-show-columns="true"
                                           data-show-columns-toggle-all="true"
                                           data-show-export="true"
                                           data-click-to-select="true"
                                           data-detail-formatter="detailFormatter"
                                           data-minimum-count-columns="2"
                                           data-id-field="id"
                                           data-side-pagination="server"
                                           data-response-handler="responseHandler">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th colspan="3">Объекты инфраструктуры</th>
                                                <th colspan="12">Виды обслуживания</th>
                                            </tr>
                                            <tr>
                                                <th>№ п/п</th>
                                                <th>Наименование объекта</th>
                                                <th>Инв./заводской №</th>
                                                <th>Янв.</th>
                                                <th>Фев.</th>
                                                <th>Мар.</th>
                                                <th>Апр.</th>
                                                <th>Май</th>
                                                <th>Июн.</th>
                                                <th>Июл.</th>
                                                <th>Авг.</th>
                                                <th>Сен.</th>
                                                <th>Окт.</th>
                                                <th>Ноя.</th>
                                                <th>Дек.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php $rowIndex = 1; @endphp
                                        @foreach($allObjectsData as $cardObject)
                                            <tr>
                                                <td></td>
                                                <td>{{ $rowIndex }}</td>
                                                <td>{{ $cardObject->name }}</td>
                                                <td>{{ $cardObject->number }}</td>
                                                @for($i = 1; $i <= 12; $i++)
                                                    <td>
                                                        @php
                                                            $maintenanceExists = false;
                                                            $maintenanceTypes = [];
                                                            foreach ($cardObject->services as $service) {
                                                                $plannedMaintenanceDate = $service->planned_maintenance_date;
                                                                $service_type = $service->service_type;
                                                                $month = date('n', strtotime($plannedMaintenanceDate));
                                                                if ($month == $i) {
                                                                    $maintenanceExists = true;
                                                                    foreach ($maintenance as $item) {
                                                                        if ($service_type == $item['service_type']) {
                                                                            $maintenanceTypes[] = $item['short_name'];
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            echo implode(', ', $maintenanceTypes);
                                                        @endphp
                                                    </td>
                                                @endfor
                                            </tr>
                                            @php $rowIndex++; @endphp
                                        @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Модальное окно для выбора карточек объектов -->
{{--        <div class="modal fade" id="addObjectCardModal" tabindex="-1" aria-labelledby="addObjectCardModalLabel" aria-hidden="true">--}}
{{--            <div class="modal-dialog">--}}
{{--                <div class="modal-content">--}}
{{--                    <div class="modal-header">--}}
{{--                        <h5 class="modal-title" id="addObjectCardModalLabel">Выберите карточку объекта</h5>--}}
{{--                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>--}}
{{--                    </div>--}}
{{--                    <div class="modal-body">--}}
{{--                        <!-- Здесь будет выпадающий список карточек объектов -->--}}
{{--                    </div>--}}
{{--                    <div class="modal-footer">--}}
{{--                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>--}}
{{--                        <button type="button" class="btn btn-primary" id="addObjectCardBtn">Добавить</button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}


        <script>
            {{--$(document).ready(function () {--}}
            {{--    // Функция для загрузки карточек объектов в модальное окно--}}
            {{--function loadObjectCards() {--}}
            {{--    $.ajax({--}}
            {{--        type: "GET",--}}
            {{--        headers: {--}}
            {{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
            {{--        },--}}
            {{--        url: "{{ route('getUnlinkedObjectCards') }}",--}}
            {{--        success: function(response) {--}}
            {{--            var modalBody = $('#addObjectCardModal').find('.modal-body');--}}
            {{--            modalBody.empty();--}}
            {{--            // Создаем выпадающий список и добавляем опции--}}
            {{--            var select = $('<select class="form-select" multiple></select>');--}}
            {{--            response.forEach(function(card) {--}}
            {{--                select.append('<option value="' + card.id + '">' + card.name + '</option>');--}}
            {{--            });--}}
            {{--            // Добавляем выпадающий список в модальное окно--}}
            {{--            modalBody.append(select);--}}
            {{--        },--}}
            {{--        error: function(error) {--}}
            {{--            console.log(error);--}}
            {{--        }--}}
            {{--    });--}}
            {{--}--}}

            {{--// Обработчик нажатия на кнопку "Добавить карточку объекта"--}}
            {{--$('#addObjectCardBtn').click(function () {--}}
            {{--    loadObjectCards(); // Загрузка карточек объектов перед открытием модального окна--}}
            {{--    $('#addObjectCardModal').modal('show'); // Открытие модального окна--}}
            {{--});--}}

            {{--// Загрузка карточек объектов при открытии модального окна--}}
            {{--$('#addObjectCardModal').on('show.bs.modal', function() {--}}
            {{--    loadObjectCards();--}}
            {{--});--}}

            {{--// Обработка нажатия кнопки "Добавить"--}}
            {{--$('#addObjectCardBtn').click(function() {--}}
            {{--    var selectedCards = [];--}}
            {{--    $('#addObjectCardModal').find('.form-select option:selected').each(function() {--}}
            {{--        selectedCards.push($(this).val());--}}
            {{--    });--}}
            {{--    // Отправка массива выбранных карточек объектов на сервер для обновления--}}
            {{--    $.ajax({--}}
            {{--        type: "POST",--}}
            {{--        headers: {--}}
            {{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
            {{--        },--}}
            {{--        url: "{{ route('addObjectCards') }}",--}}
            {{--        data: {--}}
            {{--            selectedCards: selectedCards,--}}
            {{--            graphId: "{{ $data_CardGraph->_id }}"--}}
            {{--        },--}}
            {{--        success: function(response) {--}}
            {{--            // Обновление представления после успешного добавления--}}
            {{--            location.reload();--}}
            {{--        },--}}
            {{--        error: function(error) {--}}
            {{--            console.log(error);--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}
            {{--});--}}
            // // Обработчик клика на кнопку "Добавить карточку объекта"
            // $('#addObjectCardButton').click(function () {
            //     // Открываем модальное окно
            //     $('#addObjectCardModal').modal('show');
            // });
            {{--// Обработчик нажатия кнопки "Добавить"--}}
            {{--$('#addSelectedObjectCards').click(function () {--}}
            {{--    // Собираем идентификаторы выбранных карточек объектов--}}
            {{--    var selectedCards = [];--}}
            {{--    $('input[name="selectedCardObject"]:checked').each(function() {--}}
            {{--        selectedCards.push($(this).val());--}}
            {{--    });--}}

            {{--    // Отправляем выбранные карточки объектов на сервер--}}
            {{--    $.ajax({--}}
            {{--        type: "POST",--}}
            {{--        headers: {--}}
            {{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
            {{--        },--}}
            {{--        url: "{{ route('addObjectCardsToGraph') }}", // Маршрут для обработки выбранных карточек на сервере--}}
            {{--        data: {--}}
            {{--            card_graph_id: "{{ $data_CardGraph->_id }}", // ID карточки графика--}}
            {{--            selected_cards: selectedCards // Выбранные карточки объектов--}}
            {{--        },--}}
            {{--        success: function (response) {--}}
            {{--            // Обработка успешного завершения запроса--}}
            {{--            console.log(response);--}}
            {{--            // Возможно, вы захотите обновить интерфейс после успешного добавления карточек--}}
            {{--        },--}}
            {{--        error: function (error) {--}}
            {{--            // Обработка ошибки--}}
            {{--            console.log(error);--}}
            {{--        }--}}
            {{--    });--}}

            {{--    $('#addObjectCardModal').modal('hide'); // Закрыть модальное окно после добавления--}}
            {{--});--}}
        </script>

        <script>
            $('#confirmArchiveGraph').click(function () {
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
                    url: "{{ route('archiveGraphDateButt') }}",
                    data: {
                        id: "{{ $data_CardGraph->_id }}", // Здесь нужно передать ID текущего заказа-наряда
                        date_archive: formattedDate, // Передаем текущую дату
                    },
                    success: function (response) {
                        // Обработка успешного завершения запроса
                        console.log(response);
                        location.reload();
                    },
                    error: function (error) {
                        // Обработка ошибки
                        console.log(error);
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function () {
                $("#cardGraphTab").show;

                let $table = $('#reestrCardGraph');
                var $remove = $('#remove');
                var selections = [];

                function getIdSelections() {
                    return $.map($table.bootstrapTable('getSelections'), function (row) {
                        return row.id;
                    });
                }

                function responseHandler(res) {
                    $.each(res.rows, function (i, row) {
                        row.state = $.inArray(row.id, selections) !== -1;
                    });
                    return res;
                }

                function detailFormatter(index, row) {
                    var html = [];
                    $.each(row, function (key, value) {
                        html.push('<p><b>' + key + ':</b> ' + value + '</p>');
                    });
                    return html.join('');
                }

                function initTable() {
                    $table.bootstrapTable('destroy').bootstrapTable({
                        locale: $('#locale').val(),
                        columns: [
                            {
                                field: 'state',
                                checkbox: true,
                                rowspan: 2,
                                align: 'center',
                                valign: 'middle'
                            },
                            { field: 'type', title: 'Объект инфраструктуры', align: 'center' },
                            { field: 'name', title: 'Виды обслуживания', align: 'center' },
                        ]
                    });

                    $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
                        $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
                        selections = getIdSelections();
                    });

                    $remove.click(function () {
                        let ids = getIdSelections();
                        $table.bootstrapTable('remove', {
                            field: 'id',
                            values: ids
                        });
                        $remove.prop('disabled', true);
                    });
                }

                $(function () {
                    initTable();
                    $('#locale').change(initTable);
                });
            });
        </script>
@endsection
