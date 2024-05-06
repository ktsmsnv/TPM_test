@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="reestrGraph">
            <div class="reestrGraph__btns d-flex justify-content-between">
                <button type="button" class="btn btn-secondary">Обновить реестр</button>
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
                        <table id="reestrGraph"
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
                            @foreach ($objects as $object)
                                <tr data-id="{{ $object->id }}">
                                    <td>{{ $object->card_ids }}</td>
                                    <td>{{ $object->cardObjectMain->infrastructure }}</td>
                                    <td class="tool-tip" title="открыть карточку графика">
                                        <a href="{{ route('cardGraph', ['id' => $object->cards_ids]) }}" target="_blank">
                                            {{ $object->cardObjectMain->infrastructure }}
                                        </a>
                                    </td>
                                    <td>{{ date('Y', strtotime($object->year_action)) }}</td>
                                    <td>{{ date('d.m.Y', strtotime($object->date_create)) }}</td>
                                    <td>{{ date('d.m.Y', strtotime($object->date_last_save)) }}</td>
                                    <td>{{ date('d.m.Y', strtotime($object->date_archive)) }}</td>
                                    <td>{{ $object->cardObjectServices->performer }}</td>
                                    <td>{{ $object->cardObjectServices->responsible }}</td>
                                    <td>{{ $object->curator }}</td>
                                </tr>
                            @endforeach
                            </tbody>
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
//
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
                    showConfirmDeleteRGModal();
                });

                // Функция для отображения модального окна удаления
                function showConfirmDeleteRGModal() {
                    $confirmDeleteRG.modal('show');
                }
                // Обработчик события нажатия на кнопку "Удалить" в модальном окне
                $confirmDeleteRGButton.click(function () {
                    // добавить логику для удаления элементов
                    $confirmDeleteRG.modal('hide');
                });
            }

            $(function () {
                initTable();
                $('#locale').change(initTable);
            });


        });

    </script>
@endsection
