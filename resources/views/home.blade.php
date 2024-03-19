{{--страница реестр объектов (главная) --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="reestrObject">
            <div class="reestrObject__btns d-flex justify-content-between mb-5">
                <button type="button" class="btn btn-secondary">Обновить реестр</button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success">Показать активные объекты</button>
                    <button type="button" class="btn btn-primary">Создать карточку объекта</button>
                    <button type="button" class="btn btn-primary btn-primary--2">Скопировать карточку объекта</button>
                    <button type="button" class="btn btn-light">Сформировать график TPM</button>
                    <button type="button" class="btn btn-light">Сформировать календарь TPM</button>
                    <button type="button" class="btn btn-light">Сформировать заказ-наряд TPM</button>
                </div>
            </div>
            <select class="form-control d-none" id="locale">
                <option value="ru-RU">ru-RU</option>
            </select>
            <h3 class="text-center mb-4"><strong>Реестр объектов</strong></h3>
            <div class="card">
                <div class="card-body">
                    <div class="reestrObject__table text-center">
                        <div id="toolbar">
                            <button id="remove" class="btn btn-danger" disabled>
                                <i class="fa fa-trash"></i> Удалить
                            </button>
                        </div>
                        <table id="reestrObject"
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
                                <th colspan="6">Объекты инфраструктуры</th>
                                <th colspan="8">Обслуживание TPM</th>
                            </tr>
                            <tr>
                                <th>Вид инфраструктуры</th>
                                <th>Наименование объекта</th>
                                <th>Дата ввода в эксплуатацию</th>
                                <th>Дата вывода из эксплуатации</th>
                                <th>Дата окончания аттестаци/гарантии</th>
                                <th>Инв./заводской номер</th>
                                <th>Место установки</th>
                                <th>Плановая дата обслуживания</th>
                                <th>Фактическая дата предыдущего обслуживания</th>
                                <th>Вид ближайшего обслуживания</th>
                                <th>Исполнитель</th>
                                <th>Ответственный</th>
                                <th>Заказ-наряд</th>
                                <th>Календарь TPM</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr data-id="1">
                                <td></td>
                                <td>вид инф</td>
                                <td>наим объект</td>
                                <td>дата ввода</td>
                                <td>дата вывода</td>
                                <td>дата оконч</td>
                                <td>инв зав номер</td>
                                <td>место установ</td>
                                <td>плановая дата</td>
                                <td>факт дата</td>
                                <td>вид ближ обслуж</td>
                                <td>испол</td>
                                <td>ответств</td>
                                <td>заказ-наряд</td>
                                <td>календарь трм</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно подтверждения удаления -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteKPLabel" aria-hidden="true">
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
            let $table = $('#reestrObject');
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
                        { field: 'type', title: 'Вид инфраструктуры', align: 'center' },
                        { field: 'name', title: 'Наименование объекта', align: 'center' },
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
        });

    </script>
@endsection

