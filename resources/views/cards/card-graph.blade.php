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
                <h1 class="mb-4"><strong>Карточка графика: "{{ $data_CardGraph->name ?? 'Название объекта не найдено' }}
                        "</strong></h1>
            </div>
            <div class="btns d-flex mb-5">
                <div class="d-flex gap-2">
                    <a href="/pageReestrGraph" type="button" class="btn btn-secondary me-5">Закрыть</a>
                    {{--                    <button type="button" class="btn btn-success" data-toggle="tooltip" title="ДАННАЯ КНОПКА ПОКА НЕ РАБОТАЕТ">Выгрузить WORD</button>--}}
                    <a href="{{ route('downloadGraph', ['id' => $data_CardGraph->_id]) }}" target="_blank"
                       class="btn btn-success">Выгрузить WORD</a>

                    <a href="{{ route('cardGraph-edit', ['id' => $data_CardGraph->_id]) }}"
                       type="button" class="btn btn-outline-danger">Редактировать</a>
                    <button id="getCardObject" class="btn btn-outline-primary addCardObject"
                            data-graph-id="{{ $data_CardGraph->id }}">
                        <i class="fa fa-trash"></i> Добавить карточку объекта
                    </button>
                    {{-- ПОЛУЧЕНИЕ ВСЕХ ID CardObjects, связанных с текущей карточкой графика --}}
                    <input type="hidden" id="excludedObjectIds" value="{{ json_encode($objectIds) }}">
                </div>
            </div>

            {{-- КАРТОЧКА С ВКЛАДКОЙ --}}
            <ul class="nav nav-tabs custom_tab_style1" id="cardGraphTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="mainCardGraph-tab" data-bs-toggle="tab"
                            data-bs-target="#mainCardGraph"
                            type="button" role="tab" aria-controls="mainCardGraph" aria-selected="true">ОСНОВНАЯ
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="cardGraphTabContent">
                {{-- ВКЛАДКА "ОСНОВНАЯ" --}}
                <div class="tab-pane fade show active" id="mainCardGraph" role="tabpanel"
                     aria-labelledby="mainCardGraph-tab">
                    <div id="main__blocks" class="d-grid">
                        {{-- ОБЩИЕ ДАННЫЕ --}}
                        <div class="member_card_style general">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Общие данные</h4>
                                    <button
                                        class="btn btn-primary archive_graph {{ $data_CardGraph->date_archive != null ? 'disabled' : '' }}">
                                        Заархивировать
                                    </button>
                                </div>
                                <div class="member-info--inputs d-flex gap-5">
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Вид инфраструктуры</label>
                                            <input name="infrastructure_type" id="infrastructureType"
                                                   placeholder="Введите вид инфраструктуры"
                                                   class="form-control w-100"
                                                   readonly
                                                   value="{{ $data_CardGraph->infrastructure_type ?? 'нет данных' }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Куратор</label>
                                            <input name="curator" placeholder="Введите куратора"
                                                   class="form-control w-100"
                                                   readonly value="{{$data_CardGraph->curator ?? 'нет данных' }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Год действия</label>
                                            <input name="year_action" placeholder="Введите год действия"
                                                   class="form-control w-100"
                                                   readonly value="{{$data_CardGraph->year_action ?? 'нет данных' }}">
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата создания</label>
                                            <input name="date_create" placeholder="Введите дату создания"
                                                   class="form-control w-100"
                                                   readonly
                                                   value="{{ date('d.m.Y', strtotime($data_CardGraph->date_create)) ?? 'нет данных'  }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата последнего сохранения</label>
                                            <input name="date_last_save"
                                                   placeholder="Введите дату последнего сохранения"
                                                   class="form-control w-100"
                                                   readonly
                                                   value="{{ date('d.m.Y', strtotime($data_CardGraph->date_last_save)) ?? 'нет данных'  }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата архивации</label>
                                            <input type="date" name="date_archive" placeholder="Введите дату архивации"
                                                   class="form-control w-100"
                                                   readonly style="opacity: 0.5;" data-toggle="tooltip" title="Дата архивации создаётся после нажатия на кнопку 'Заархивировать'"
                                                   value="{{ isset($data_CardGraph->date_archive) ?$data_CardGraph->date_archive : 'нет данных' }}">
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
                                                                       if (!$service->checked) {
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


        <!-- Модальное окно подтверждения архивации графика -->
        <div class="modal fade" id="confirmArchiveModal" tabindex="-1" aria-labelledby="confirmArchiveModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmArchiveModalLabel">Подтверждение архивации графика</h5>
                        <button type="button" class="btn-close" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Вы уверены, что хотите архивировать данный график объекта
                        "{{$data_CardGraph->name}}" ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="button" class="btn btn-danger" id="confirmArchiveButton">Заархивировать</button>
                    </div>
                </div>
            </div>
        </div>

        {{--        Модальное окно добавления карточки объекта в карточку графика--}}
        <div class="modal fade" id="confirmAddCardObjectModal" tabindex="-1" aria-labelledby="confirmAddCardObjectLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmAddCardObjectModalLabel">Выберите карточку(-и) объекта, которую(-ые) хотите добавить</h5>
                        <button type="button" class="btn-close" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="cardObjectsSelect">Выберите карточку(-и) объекта:</label>
                        <select id="cardObjectsSelect" class="form-select" multiple>
                            <!-- Здесь будут отображены карточки объектов -->
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Добавить</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    </div>
                </div>
            </div>
        </div>

        {{--        МОДАЛЬНОЕ ОКНО АРХИВИРОВАНИЯ ГРАФИКА--}}
        <script>
            // Обработчик события нажатия на кнопку "Архивировать"
            $('.archive_graph').click(function () {
                // Открываем модальное окно с вопросом об архивации
                $('#confirmArchiveModal').modal('show');
            });
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

                let $getCardObject = $('#getCardObject');
                let $table = $('#reestrCardGraph');
                var $remove = $('#remove');
                let $confirmAddCardObject = $('#confirmAddCardObjectModal');
                var selections = [];

                function getIdSelections() {
                    return $.map($table.bootstrapTable('getSelections'), function (row) {
                        return row.id;
                    });
                }

                function showConfirmAddCardObjectModal() {
                    $confirmAddCardObject.modal('show');
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
                            {field: 'type', title: 'Объект инфраструктуры', align: 'center'},
                            {field: 'name', title: 'Виды обслуживания', align: 'center'},
                        ]
                    });

                    $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
                        selections = getIdSelections();
                    });

                    $getCardObject.click(function () {
                        let ids = getIdSelections();
                        $table.bootstrapTable('remove', {
                            field: 'id',
                            values: ids
                        });
                        $remove.prop('disabled', true);
                    });
                }

                $getCardObject.click(function () {
                    showConfirmAddCardObjectModal();
                })

                $('#getCardObject').click(function () {
                    // Получаем вид инфраструктуры из скрытого поля
                    var infrastructureType = $('#infrastructureType').val();
                    var excludedObjectIds = JSON.parse($('#excludedObjectIds').val());

                    // Отправляем AJAX запрос на сервер
                    $.ajax({
                        url: '/get-all-card-objects',
                        type: 'GET',
                        data: {
                            infrastructure_type: infrastructureType,
                            excluded_object_ids: excludedObjectIds
                        },
                        success: function (response) {
                            // Очищаем текущие элементы в select
                            $('#cardObjectsSelect').empty();
                            // Добавляем полученные карточки объектов в select
                            if (response.length > 0) {
                                $.each(response, function (index, cardObject) {
                                    var optionText = (index + 1) + ') ' + cardObject.name;
                                    $('#cardObjectsSelect').append('<option value="' + cardObject.id + '">' + optionText + '</option>');
                                });
                            } else {
                                $('#cardObjectsSelect').append('<option value="нет объектов">Нет объектов</option>');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error(error);
                        }
                    });
                });

                $('#confirmAddCardObjectModal .btn-success').click(function(){
                    // Получаем выбранные карточки объектов из select
                    var selectedCardObjects = $('#cardObjectsSelect').val();

                    // Получаем id карточки графика из data-атрибута кнопки "Добавить"
                    var graphId = $(this).data('graph-id');

                    console.log('Selected Card Objects:', selectedCardObjects);
                    console.log('Graph ID:', graphId);

                    // Отправляем AJAX запрос на сервер для добавления карточек объектов к карточке графика
                    $.ajax({
                        url: '/add-card-objects-to-graph',
                        type: 'POST',
                        data: {
                            card_objects: selectedCardObjects,
                            graph_id: graphId, // Предположим, что у вас есть переменная graphId с идентификатором карточки графика
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response){
                            // Перезагружаем страницу после успешного добавления
                            if(response.success){
                                location.reload();
                            }
                        },
                        error: function(xhr, status, error){
                            console.error('Error:', error);
                        }
                    });
                });

                $(function () {
                    initTable();
                    $('#locale').change(initTable);
                });

            });
        </script>
@endsection
