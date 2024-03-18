{{--страница реестр заказ-нарядов --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="reestrObject">
            <div class="reestrObject__btns d-flex mb-5">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary me-5">Обновить реестр</button>
                    <button type="button" class="btn btn-primary">Выбрать период</button>
                    <button type="button" class="btn btn-success">Показать активные заказ-наряды</button>
                </div>
            </div>
            <select class="form-control d-none" id="locale">
                <option value="ru-RU">ru-RU</option>
            </select>
            <h3 class="text-center mb-4"><strong>Реестр заказ-нарядов ТРМ</strong></h3>
            <div class="card">
                <div class="card-body">
                    <div class="reestrObject__table text-center">
                        <div id="toolbar">
                            <button id="remove" class="btn btn-danger" disabled>
                                <i class="fa fa-trash"></i> Удалить
                            </button>
                        </div>
                        <table id="reestrWorkOrder"
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
                                <th colspan="8">Заказ-наряды TPM</th>
                                <th></th>
                                <th></th>
                                <th colspan="3">Ответственные</th>
                            </tr>
                            <tr>
                                <td></td>
                                <th>Вид инфраструктуры</th>
                                <th>Наименование объекта</th>
                                <th>Инв./заводской номер</th>
                                <th>Место установки</th>
                                <th>Вид обслуживания</th>
                                <th>Плановая дата</th>
                                <th>Фактическая дата</th>
                                <th>Статус</th>
                                <th>Дата сорздания</th>
                                <th>Дата последнего сохранения</th>
                                <th>Исполнитель</th>
                                <th>Ответственный</th>
                                <th>Куратор</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr data-id="1">
                                <td></td>
                                <th>Вид инфраструктуры</th>
                                <th>Наименование объекта</th>
                                <th>Инв./заводской номер</th>
                                <th>Место установки</th>
                                <th>Вид обслуживания</th>
                                <th>Плановая дата</th>
                                <th>Фактическая дата</th>
                                <th>Статус</th>
                                <th>Дата сорздания</th>
                                <th>Дата последнего сохранения</th>
                                <th>Исполнитель</th>
                                <th>Ответственный</th>
                                <th>Куратор</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            let $table = $('#reestrWorkOrder');
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
                        { field: 'type', title: 'Заказ-наряды ТРМ', align: 'center' },
                        { field: 'name1', title: '', align: 'center' },
                        { field: 'name2', title: '', align: 'center' },
                        { field: 'name', title: 'Ответственные', align: 'center' },
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
