@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="reestrGraph">
            <div class="reestrGraph__btns d-flex justify-content-between">
                <button type="button" class="btn btn-secondary refreshTable" data-toggle="tooltip"
                        title="показать последние данные">Обновить реестр
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success">Выбрать период действия</button>
                    <button type="button" class="btn btn-success">Показать активные графики</button>
                </div>
            </div>
            <select class="form-control d-none" id="locale">
                <option value="ru-RU">ru-RU</option>
            </select>
            <h3 class="text-center mb-4"><strong>Реестр графиков</strong></h3>
            <div class="card">
                <div class="card-body">
                    <div class="reestrGraph__table text-center">
                        <div id="toolbar">
                            <button id="remove" class="btn btn-danger" disabled>
                                <i class="fa fa-trash"></i> Удалить
                            </button>
                        </div>
                        <table id="reestrGraph" data-url="/get-cardGraph"
                               data-toolbar="#toolbar"
                               data-search="true"
                               data-show-refresh="true"
                               data-show-toggle="true"
                               data-show-fullscreen="true"
                               data-show-columns="true"
                               data-show-columns-toggle-all="true"
{{--                               data-detail-view="true"--}}
                               data-show-export="true"
                               data-click-to-select="true"
                               data-detail-formatter="detailFormatter"
                               data-minimum-count-columns="2"
                               data-show-pagination-switch="true"
                               data-pagination="true"
                               data-id-field="id"
                               data-show-footer="true"
                               data-side-pagination="server"
                               data-response-handler="responseHandler">
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th></th>--}}
{{--                                <th colspan="6"></th>--}}
{{--                                <th colspan="3"></th>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <th>Вид инфраструктуры</th>--}}
{{--                                <th>Наименование графика</th>--}}
{{--                                <th>Год действия</th>--}}
{{--                                <th>Дата создания</th>--}}
{{--                                <th>Дата последнего сохранения</th>--}}
{{--                                <th>Дата архивации</th>--}}
{{--                                <th>Исполнитель</th>--}}
{{--                                <th>Ответственный</th>--}}
{{--                                <th>Куратор</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            @foreach ($cardGraphs as $object)--}}
{{--                                <tr data-id="{{ $object->id }}">--}}
{{--                                    <td>{{ $object->_id }}</td>--}}
{{--                                    <td>{{ $object->infrastructure_type }}</td>--}}
{{--                                    <td class="tool-tip" title="открыть карточку графика">--}}
{{--                                        <a href="{{ route('cardGraph', ['id' => $object->_id]) }}" target="_blank">--}}
{{--                                            {{ $object->name }}--}}
{{--                                        </a>--}}
{{--                                    </td>--}}
{{--                                    <td>{{ date('Y', strtotime($object->year_action)) }}</td>--}}
{{--                                    <td>{{ date('d.m.Y', strtotime($object->date_create)) }}</td>--}}
{{--                                    <td>{{ date('d.m.Y', strtotime($object->date_last_save)) }}</td>--}}
{{--                                    <td>{{ date('d.m.Y', strtotime($object->date_archive)) }}</td>--}}
{{--                                     Проверка на существование записи в cardObjectServices--}}
{{--                                    <td>--}}
{{--                                        @if ($object->cardObjectServices->isNotEmpty())--}}
{{--                                            @foreach ($object->cardObjectServices as $service)--}}
{{--                                                {{ $service->performer }}<br>--}}
{{--                                            @endforeach--}}
{{--                                        @else--}}
{{--                                            Нет данных--}}
{{--                                        @endif--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        @if ($object->cardObjectServices->isNotEmpty())--}}
{{--                                            @foreach ($object->cardObjectServices as $service)--}}
{{--                                                {{ $service->responsible }}<br>--}}
{{--                                            @endforeach--}}
{{--                                        @else--}}
{{--                                            Нет данных--}}
{{--                                        @endif--}}
{{--                                    </td>--}}
{{--                                    <td>{{ $object->curator }}</td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                            </tbody>--}}

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно подтверждения удаления -->
    <div class="modal fade" id="confirmDeleteRGModal" tabindex="-1" aria-labelledby="confirmDeleteRGLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteRGModalLabel">Подтверждение удаления</h5>
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Вы уверены, что хотите удалить выбранные элементы?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteRGButton">Удалить</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            let $table = $('#reestrGraph');
            var $remove = $('#remove');
            var selections = [];
            let $confirmDeleteRG = $('#confirmDeleteRGModal'); // Ссылка на модальное окно
            let $confirmDeleteRGButton = $('#confirmDeleteRGButton'); // Кнопка "Удалить" в модальном окне

            // ------------------------------------ выбор полей checked ------------------------------------
            function getIdSelections() {
                return $.map($table.bootstrapTable('getSelections'), function (row) {
                    return row.id;
                });
            }

            // ------------------------------------ Функция для получения данных с сервера ------------------------------------
            function getObjectsFromServer() {
                return $.get('/get-cardGraph'); // Возвращаем Promise
            }

            // function responseHandler(res) {
            //     $.each(res.rows, function (i, row) {
            //         row.state = $.inArray(row.id, selections) !== -1;
            //     });
            //     return res;
            // }
            //
            // function detailFormatter(index, row) {
            //     var html = [];
            //     $.each(row, function (key, value) {
            //         html.push('<p><b>' + key + ':</b> ' + value + '</p>');
            //     });
            //     return html.join('');
            // }

            // ------------------------------------ Функция для обновления таблицы ------------------------------------
            function refreshTable() {
                getObjectsFromServer().done(function(data) {
                    initTable(data); // Инициализируем таблицу с новыми данными
                });
            }

            $('.refreshTable').click(function () {
                refreshTable();
            });

            function initTable(data) {
                console.log('Данные:', data);
                $table.bootstrapTable('destroy').bootstrapTable({
                    locale: $('#locale').val(),
                    pagination: true,
                    pageNumber: 1,
                    pageSize: 10,
                    pageList: [10, 25, 50, 'all'],
                    columns: [
                        [
                            {colspan: 8, title: 'Графики TPM', align: 'center'},
                            {colspan: 3, title: 'Ответственные', align: 'center'},
                        ],
                        [
                            {field: 'state', checkbox: true, align: 'center', valign: 'middle'},
                            {title: 'Item ID', field: 'id', align: 'center', valign: 'middle', visible: false},
                            {title: 'Вид инфраструктуры', field: 'infrastructure_type', align: 'center'},
                            {
                                title: 'Наименование графика',
                                field: 'name',
                                align: 'center',
                                formatter: function (value, row) {
                                    // Создаем ссылку с помощью значения поля "name"
                                    return '<a href="/pageReestrGraph/card-graph/' + row.id + '" target="_blank"' +
                                        'data-toggle="tooltip" title="открыть карточку графика">' + value + '</a>';
                                }
                            },
                            {title: 'Год действия', field: 'year_action', align: 'center'},
                            {
                                title: 'Дата создания', field: 'date_create', align: 'center',
                                formatter: function (value, row) {
                                    // Преобразование даты в нужный формат (день-месяц-год)
                                    return new Date(value).toLocaleDateString('ru-RU');
                                }
                            },
                            {
                                title: 'Дата последнего сохранения', field: 'date_last_save', align: 'center',
                                formatter: function (value, row) {
                                    // Преобразование даты в нужный формат (день-месяц-год)
                                    return new Date(value).toLocaleDateString('ru-RU');
                                }
                            },
                            {
                                title: 'Дата архивации', field: 'date_archive', align: 'center',
                                formatter: function (value, row) {
                                    // Преобразование даты в нужный формат (день-месяц-год)
                                    return new Date(value).toLocaleDateString('ru-RU');
                                }
                            },
                            {title: 'Исполнитель', field: 'performer', align: 'center'},
                            {title: 'Ответственный', field: 'responsible', align: 'center'},
                            {title: 'Куратор', field: 'curator', align: 'center'},
                        ],
                    ],
                    data: data,
                    ajaxOptions: {
                        success: function (data) {
                            $table.bootstrapTable('load', data);
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    }
                });
            }

            //Вызов функции для получения данных с сервера
            getObjectsFromServer().done(function(data) {
                initTable(data); // Инициализируем таблицу с новыми данными
            });
//
            $table.on('check.bs.table uncheck.bs.table ' + ' check-all.bs.table uncheck-all.bs.table', function () {
                $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
                selections = getIdSelections();
            });

            $remove.click(function () {
                let ids = getIdSelections();
                if (ids.length > 0) {
                    showConfirmDeleteRGModal();
                }
            });

            // Функция для отображения модального окна удаления
            function showConfirmDeleteRGModal() {
                $confirmDeleteRG.modal('show');
            }
            // Обработчик события нажатия на кнопку "Удалить" в модальном окне
            $confirmDeleteRGButton.click(function () {
                let ids = getIdSelections();
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('delete-cardGraph') }}",
                    data: {ids: ids},
                    success: function (response) {
                        // Обновить таблицу после успешного удаления
                        refreshTable();
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
                $confirmDeleteRG.modal('hide');
            });

            // ------------------------------------ Показать активные объекты ------------------------------------
            let isActiveFilter = false; // Флаг, указывающий на текущее состояние фильтрации активных объектов
            // Обработчик события нажатия на кнопку "Показать активные объекты"
            $('#showActiveBtn').click(function () {
                if (isActiveFilter) {
                    resetFilter(); // Если фильтрация активна, сбрасываем её
                } else {
                    showActiveCardGraphs(); // Если фильтрация неактивна, применяем фильтр
                }
            });
            // Функция для отображения только активных объектов
            function showActiveCardGraphs() {
                let data = $table.bootstrapTable('getData');
                let activeObjects = data.filter(function (row) {
                    return !row.date_usage_end;
                });

                // Функция для отображения модального окна удаления
                function showConfirmDeleteModal() {
                    $confirmDeleteRG.modal('show');
                }
                // Обработчик события нажатия на кнопку "Удалить" в модальном окне
                $confirmDeleteRGButton.click(function () {
                    // добавить логику для удаления элементов
                    $confirmDeleteRG.modal('hide');
                });
                $table.bootstrapTable('load', activeObjects);
                isActiveFilter = true; // Устанавливаем флаг фильтрации в активное состояние
            }
            // Функция для сброса фильтрации и отображения всех объектов
            function resetFilter() {
                refreshTable(); // Перезагружаем таблицу, чтобы сбросить фильтр
                isActiveFilter = false; // Устанавливаем флаг фильтрации в неактивное состояние
            }


            // $(function () {
            //     initTable();
            //     $('#locale').change(initTable);
            // });


        });

    </script>
@endsection
