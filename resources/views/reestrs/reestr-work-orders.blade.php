{{--страница реестр заказ-нарядов --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="reestrObject">
            <div class="reestrObject__btns d-flex mb-5">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary me-5" data-toggle="tooltip" title="показать последние данные">Обновить реестр</button>
                    <button type="button" class="btn btn-primary" id="togglePeriodSelection" data-toggle="tooltip" title="показать записи за период">Выбрать период</button>
                    <button type="button" class="btn btn-success" data-toggle="tooltip" title="статус 'в работе'">Показать активные заказ-наряды</button>
                </div>
            </div>
            <div class="collapse" id="periodSelection">
                <div class="card card-body position-absolute" style="top: 50px;left: 200px;z-index: 99;">
                    <!-- диапазон дат -->
                    <div class="form-group mb-3">
                        <label for="startDate">Начальная дата:</label>
                        <input type="date" class="form-control" id="startDate">
                    </div>
                    <div class="form-group mb-3">
                        <label for="endDate">Конечная дата:</label>
                        <input type="date" class="form-control" id="endDate">
                    </div>
                    <button type="button" class="btn btn-primary" id="applyButton">Применить</button>
                </div>
            </div>
            <div id="selectedPeriod" class="mt-3"></div>

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
                                <th>из карточки заказа</th>
                                <td><a href="/reestr-work-orders/card-work-order" target="_blank" class="tool-tip" title="открыть карточку графика">Сварочное оборудование JASIC MIG 3500 TECH N222</a></td>
                                <th>из карточки заказа</th>
                                <th>из карточки заказа</th>
                                <th>из карточки заказа</th>
                                <th>из карточки заказа</th>
                                <th>из карточки заказа</th>
                                <th>из карточки заказа</th>
                                <th>из карточки заказа</th>
                                <th>из карточки заказа</th>
                                <th>из карточки заказа</th>
                                <th>из карточки заказа</th>
                                <th>из карточки заказа</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно подтверждения удаления -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteKPLabel"
         aria-hidden="true">
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

    {{-- настройка таблицы и модалка удаления --}}
    <script>
        $(document).ready(function () {
            let $table = $('#reestrWorkOrder');
            let $remove = $('#remove');
            let selections = [];
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
                let html = [];
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
                        {field: 'type', title: 'Заказ-наряды ТРМ', align: 'center'},
                        {field: 'name1', title: '', align: 'center'},
                        {field: 'name2', title: '', align: 'center'},
                        {field: 'name', title: 'Ответственные', align: 'center'},
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

    {{-- выбрать период--}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const periodSelection = document.getElementById('periodSelection');
            const togglePeriodSelection = document.getElementById('togglePeriodSelection');
            const applyButton = document.getElementById('applyButton');
            const selectedPeriod = document.getElementById('selectedPeriod');

            // Скрыть блок выбора периода при нажатии вне его области
            document.addEventListener('click', function (event) {
                if (!periodSelection.contains(event.target) && event.target !== togglePeriodSelection) {
                    periodSelection.classList.remove('show');
                }
            });

            // Переключение видимости блока выбора периода при нажатии на кнопку
            togglePeriodSelection.addEventListener('click', function () {
                if (periodSelection.classList.contains('show')) {
                    periodSelection.classList.remove('show');
                } else {
                    periodSelection.classList.add('show');
                }
            });

            // Обработка нажатия на кнопку "Применить"
            applyButton.addEventListener('click', function () {
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;

                // Отобразить выбранный период под блоком с кнопками
                selectedPeriod.innerHTML = `
                <div class="alert alert-info" role="alert">
                    Выбранный период: с ${endDate.split('-').reverse().join('-')}  по ${startDate.split('-').reverse().join('-')}
                    <button type="button" class="btn btn-danger ms-3" id="resetPeriodButton">Сбросить период</button>
                </div>`;

                // Добавляем обработчик клика на кнопку "Сбросить период"
                document.getElementById('resetPeriodButton').addEventListener('click', function() {
                    selectedPeriod.innerHTML = '';
                });
            });
        });
    </script>
@endsection
