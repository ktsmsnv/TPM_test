@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="reestrGraph">
            <div class="reestrGraph__btns d-flex justify-content-between">
                <button type="button" class="btn btn-secondary">Обновить реестр</button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success">Выбрать период действия</button>
                    <button type="button" class="btn btn-success">Показать активные графики</button>
                    <button type="button" class="btn btn-primary">Реестр графиков ТРМ</button>
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
                        <table id="reestrGraph"
                               data-toolbar="#toolbar"
                               data-search="true"
                               data-show-refresh="true"
                               data-show-toggle="true"
                               data-show-fullscreen="true"
                               data-show-columns="true"
                               data-show-columns-toggle-all="true"
                               data-detail-view="true"
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
                            <thead>
                            <tr>
                                <th></th>
                                <th colspan="6"></th>
                                <th colspan="3"></th>
                            </tr>
                            <tr>
                                <th>Вид инфраструктуры</th>
                                <th>Наименование графика</th>
                                <th>Год действия</th>
                                <th>Дата создания</th>
                                <th>Дата последнего сохранения</th>
                                <th>Дата архивации</th>
                                <th>Исполнитель</th>
                                <th>Ответственный</th>
                                <th>Куратор</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr data-id="1">
                                <td></td>
                                <td>из карточки графика</td>
                                <td>ГОДОВОЙ ГРАФИК TPM ОБЪЕКТОВ ТЕХНОЛОГИЧЕСКОЙ  ИНФРАСТРУКТУРЫ</td>
                                <td>из карточки графика</td>
                                <td>из карточки графика</td>
                                <td>из карточки графика</td>
                                <td>из карточки графика</td>
                                <td>из карточки графика</td>
                                <td>из карточки графика</td>
                                <td>из карточки графика</td>
                            </tr>
{{--                            @foreach ($pageReestrGraph as $item)--}}
{{--                                <tr class="editable-row" data-id="{{ $item->id }}">--}}
{{--                                    <td>{{ $item->id }}</td>--}}
{{--                                    <td>{{ $item->typeInfrastruct }}</td>--}}
{{--                                    <td>{{ $item->nameGraph }}</td>--}}
{{--                                    <td>{{ year($item->yearAction) }}</td>--}}
{{--                                    <td>{{ date('d.m.Y', strtotime($item->dateCreation)) }}</td>--}}
{{--                                    <td>{{ date('d.m.Y', strtotime($item->dateLastSave)) }}</td>--}}
{{--                                    <td>{{ date('d.m.Y', strtotime($item->dateArchiv)) }}</td>--}}
{{--                                    <td>{{ $item->actor }}</td>--}}
{{--                                    <td>{{ $item->responsible }}</td>--}}
{{--                                    <td>{{ $item->curator }}</td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно подтверждения удаления -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteRGLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Подтверждение удаления</h5>
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Вы уверены, что хотите удалить выбранные элементы?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Удалить</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            let $table = $('#reestrGraph');
            var $remove = $('#remove');
            var selections = [];
            let $confirmDelete = $('#confirmDeleteModal'); // Ссылка на модальное окно
            let $confirmDeleteButton = $('#confirmDeleteButton'); // Кнопка "Удалить" в модальном окне


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
                        { field: 'graphTRM', title: 'Графики TPM', align: 'center' },
                        { field: 'resp', title: 'Ответственные', align: 'center' },
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
                    showConfirmDeleteModal();
                });

                // Функция для отображения модального окна удаления
                function showConfirmDeleteModal() {
                    $confirmDelete.modal('show');
                }
                // Обработчик события нажатия на кнопку "Удалить" в модальном окне
                $confirmDeleteButton.click(function () {
                    // добавить логику для удаления элементов
                    $confirmDelete.modal('hide');
                });
            }

            $(function () {
                initTable();
                $('#locale').change(initTable);
            });
            // var $table = $('#reestrGraphTable');
            // initTable($table);
            // // инициализация таблицы и ее настроек
            // function initTable($table) {
            //     $table.bootstrapTable({
            //         locale: $('#locale').val(),
            //         pagination: true,
            //         pageNumber: 1,
            //         pageSize: 5,
            //         pageList: [5, 15, 50, 'all'],
            //         columns: [
            //             // {
            //             //     field: 'id',
            //             //     title: '№',
            //             //     valign: 'middle',
            //             //     sortable: true,
            //             // },
            //             {
            //                 field: 'typeInfrastruct',
            //                 title: 'Вид инфраструктуры',
            //                 valign: 'middle',
            //                 sortable: true,
            //             },
            //             {
            //                 field: 'nameGraph',
            //                 title: 'Наименование графика',
            //                 valign: 'middle',
            //                 sortable: true
            //             },
            //             {
            //                 field: 'yearAction',
            //                 title: 'Год действия',
            //                 valign: 'middle',
            //                 sortable: true
            //             },
            //             {
            //                 field: 'dateCreation',
            //                 title: 'Дата создания',
            //                 valign: 'middle',
            //                 sortable: true
            //             },
            //             {
            //                 field: 'dateLastSave',
            //                 title: 'Дата последнего сохранения',
            //                 valign: 'middle',
            //                 sortable: true
            //             },
            //             {
            //                 field: 'dateArchiv',
            //                 title: 'Дата архивации',
            //                 valign: 'middle',
            //                 sortable: true
            //             },
            //             {
            //                 field: 'actor',
            //                 title: 'Исполнитель',
            //                 valign: 'middle',
            //                 sortable: true
            //             },
            //             {
            //                 field: 'responsible',
            //                 title: 'Ответственный',
            //                 valign: 'middle',
            //                 sortable: true
            //             },
            //             {
            //                 field: 'curator',
            //                 title: 'Куратор',
            //                 valign: 'middle',
            //                 sortable: true
            //             }
            //         ]
            //     });
            // }

        });

    </script>
@endsection
