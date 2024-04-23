{{--страница реестр объектов (главная) --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="reestrObject">
            <div class="reestrObject__btns d-flex justify-content-between mb-5">
                <button type="button" class="btn btn-secondary refreshTable" data-toggle="tooltip"
                        title="показать последние данные">Обновить реестр
                </button>
                <div class="d-flex gap-2">
                    <a id="showActiveBtn" type="button" class="btn btn-success" data-toggle="tooltip"
                       title="без даты вывода объекта из эксплуатации">Показать активные объекты</a>
                    <a href="/home/card-object-create" target="_blank" type="button" class="btn btn-primary">Создать карточку объекта</a>
                    <a type="button" class="btn btn-primary btn-primary--2 copy_cardObject">Скопировать карточку объекта</a>
                    <button id="generateGraphTPM" class="btn btn-light" disabled>Сформировать график TPM</button>
                    <a type="button" class="btn btn-light">Сформировать календарь TPM</a>
                    <a type="button" class="btn btn-light">Сформировать заказ-наряд TPM</a>
                </div>
            </div>
            <label for="locale"></label>
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
                        <table id="reestrObject" data-url="/get-objects"
                               data-toolbar="#toolbar" data-search=""
                               data-show-refresh="true" data-show-toggle="true" data-show-fullscreen="true"
                               data-show-columns="true" data-show-columns-toggle-all="true"
                               data-show-export="true" data-click-to-select="true" data-minimum-count-columns="12"
                               data-show-pagination-switch="true" data-pagination="true"
                               data-id-field="id" data-response-handler="responseHandler">
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
            let $table = $('#reestrObject');
            let $remove = $('#remove');
            let selections = [];
            var $generateGraphTPM = $('#generateGraphTPM');
            let $confirmDelete = $('#confirmDeleteModal'); // Ссылка на модальное окно
            let $confirmDeleteButton = $('#confirmDeleteButton'); // Кнопка "Удалить" в модальном окне

            // выбор полей checked
            function getIdSelections() {
                return $.map($table.bootstrapTable('getSelections'), function (row) {
                    return row.id
                });
            }


            // Функция для получения данных с сервера
            function getObjectsFromServer() {
                return $.get('/get-objects'); // Возвращаем Promise
            }

            // Функция для обновления таблицы
            function refreshTable() {
                getObjectsFromServer().done(function(data) {
                    initTable(data); // Инициализируем таблицу с новыми данными
                });
            }

            $('.refreshTable').click(function () {
                refreshTable();
            });
            // Функция для инициализации таблицы
            function initTable(data) {
                console.log('Данные:', data);
                // Инициализация таблицы с данными
                $table.bootstrapTable('destroy').bootstrapTable({
                    locale: $('#locale').val(),
                    pagination: true,
                    pageNumber: 1,
                    pageSize: 10,
                    pageList: [10, 25, 50, 'all'],
                    columns: [
                        [
                            {colspan: 8, title: 'Объекты инфраструктуры', align: 'center'},
                            {colspan: 8, title: 'Обслуживание ТРМ', align: 'center'},
                        ],
                        [
                            {field: 'state', checkbox: true, align: 'center', valign: 'middle'},
                            {title: 'Item ID', field: 'id', align: 'center', valign: 'middle',  visible: false },
                            {title: 'Вид инфраструктуры', field: 'infrastructure', align: 'center'},
                            {
                                title: 'Наименование объекта',
                                field: 'name',
                                align: 'center',
                                formatter: function(value, row) {
                                    // Создаем ссылку с помощью значения поля "name"
                                    return '<a href="/home/card-object/' + row.id + '" target="_blank">' + value + '</a>';
                                }
                            },

                            {title: 'Дата ввода в эксплуатацию', field: 'date_arrival', align: 'center'},
                            {title: 'Дата вывода из эксплуатации', field: 'date_usage_end', align: 'center'},
                            {title: 'Дата окончания аттестации/гарантии', field: 'date_cert_end', align: 'center'},
                            {title: 'Инв./заводской номер', field: 'number', align: 'center'},

                            {title: 'Место установки', field: 'location', align: 'center'},
                            {
                                title: 'Плановая дата обслуживания',
                                field: 'planned_maintenance_date',
                                align: 'center',
                                formatter: function(value, row) {
                                    let nearestMaintenanceDate = null;
                                    if (row.services && Array.isArray(row.services) && row.services.length > 0) {
                                        row.services.forEach(function(service) {
                                            if (!nearestMaintenanceDate || new Date(service.planned_maintenance_date) < new Date(nearestMaintenanceDate)) {
                                                nearestMaintenanceDate = service.planned_maintenance_date;
                                            }
                                        });
                                        return nearestMaintenanceDate ? nearestMaintenanceDate : 'Нет запланированных обслуживаний';
                                    } else {
                                        return 'Нет запланированных обслуживаний';
                                    }
                                }
                            },
                            {
                                title: 'Фактическая дата предыдущего обслуживания',
                                field: 'prev_maintenance_date',
                                align: 'center',
                                formatter: function(value, row) {
                                    let nearestService = null;
                                    if (row.services && Array.isArray(row.services) && row.services.length > 0) {
                                        row.services.forEach(function(service) {
                                            if (!nearestService || new Date(service.planned_maintenance_date) < new Date(nearestService.planned_maintenance_date)) {
                                                nearestService = service;
                                            }
                                        });
                                        return nearestService ? nearestService.prev_maintenance_date : 'Нет даты предыдущего обслуживания';
                                    } else {
                                        return 'Нет даты предыдущего обслуживания';
                                    }
                                }
                            },
                            {
                                title: 'Вид ближайшего обслуживания',
                                field: 'service_type',
                                align: 'center',
                                formatter: function(value, row) {
                                    let nearestService = null;
                                    if (row.services && Array.isArray(row.services) && row.services.length > 0) {
                                        row.services.forEach(function(service) {
                                            if (!nearestService || new Date(service.planned_maintenance_date) < new Date(nearestService.planned_maintenance_date)) {
                                                nearestService = service;
                                            }
                                        });
                                        return nearestService ? nearestService.service_type : 'Нет вида ближайшего обслуживания';
                                    } else {
                                        return 'Нет вида ближайшего обслуживания';
                                    }
                                }
                            },
                            {
                                title: 'Исполнитель',
                                field: 'performer',
                                align: 'center',
                                formatter: function(value, row) {
                                    let nearestService = null;
                                    if (row.services && Array.isArray(row.services) && row.services.length > 0) {
                                        row.services.forEach(function(service) {
                                            if (!nearestService || new Date(service.planned_maintenance_date) < new Date(nearestService.planned_maintenance_date)) {
                                                nearestService = service;
                                            }
                                        });
                                        return nearestService ? nearestService.performer : 'Нет исполнителя';
                                    } else {
                                        return 'Нет исполнителя';
                                    }
                                }
                            },
                            {
                                title: 'Ответственный',
                                field: 'responsible',
                                align: 'center',
                                formatter: function(value, row) {
                                    let nearestService = null;
                                    if (row.services && Array.isArray(row.services) && row.services.length > 0) {
                                        row.services.forEach(function(service) {
                                            if (!nearestService || new Date(service.planned_maintenance_date) < new Date(nearestService.planned_maintenance_date)) {
                                                nearestService = service;
                                            }
                                        });
                                        return nearestService ? nearestService.responsible : 'Нет ответственного';
                                    } else {
                                        return 'Нет ответственного';
                                    }
                                }
                            },
                            {
                                title: 'Заказ-наряд',
                                field: 'work_order',
                                align: 'center',
                                formatter: function(value, row) {
                                    return '<a href="/reestr-work-orders/card-work-order" class="tool-tip" title="открыть карточку заказ-наряда">' + value + '</a>';
                                }
                            },
                            {
                                title: 'Календарь TPM',
                                field: 'tpm_calendar',
                                align: 'center',
                                formatter: function(value, row) {
                                    return '<a href="" class="tool-tip" title="открыть карточку календаря">' + value + '</a>';
                                }
                            }
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

            $table.on('check.bs.table uncheck.bs.table ' + 'check-all.bs.table uncheck-all.bs.table',
                function () {
                    $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
                    $generateGraphTPM.prop('disabled', !$table.bootstrapTable('getSelections').length)
                    selections = getIdSelections();
                });





            // обработчик нажатия по кнопке удаления
            $remove.click(function () {
                let ids = getIdSelections();
                if (ids.length > 0) {
                    showConfirmDeleteModal();
                }
            });

            $generateGraphTPM.click(function (){
                let ids = getIdSelections();
                console.log(ids);
                if (ids.length > 0) {
                    // Сформируйте URL с ID выбранных записей и перенаправьте пользователя на страницу формирования графика TPM
                    window.location.href = "/pageReestrGraph/card-graph-create?ids=" + ids.join(',');
                }
            });

            // Функция для отображения модального окна удаления
            function showConfirmDeleteModal() {
                $confirmDelete.modal('show');
            }
            // Обработчик события нажатия на кнопку "Удалить" в модальном окне
            $confirmDeleteButton.click(function () {
                let ids = getIdSelections();
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('delete-cardObject') }}",
                    data: {ids: ids},
                    success: function (response) {
                        // Обновить таблицу после успешного удаления
                        refreshTable();
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
                $confirmDelete.modal('hide');
            });




            let isActiveFilter = false; // Флаг, указывающий на текущее состояние фильтрации активных объектов
            // Обработчик события нажатия на кнопку "Показать активные объекты"
            $('#showActiveBtn').click(function () {
                if (isActiveFilter) {
                    resetFilter(); // Если фильтрация активна, сбрасываем её
                } else {
                    showActiveObjects(); // Если фильтрация неактивна, применяем фильтр
                }
            });
            // Функция для отображения только активных объектов
            function showActiveObjects() {
                let data = $table.bootstrapTable('getData');
                let activeObjects = data.filter(function (row) {
                    return !row.date_usage_end;
                });
                $table.bootstrapTable('load', activeObjects);
                isActiveFilter = true; // Устанавливаем флаг фильтрации в активное состояние
            }
            // Функция для сброса фильтрации и отображения всех объектов
            function resetFilter() {
                refreshTable(); // Перезагружаем таблицу, чтобы сбросить фильтр
                isActiveFilter = false; // Устанавливаем флаг фильтрации в неактивное состояние
            }


            // создание копии карточки объекта
            $('.copy_cardObject').click(function () {
                let selectedRows = $table.bootstrapTable('getSelections');
                let ids = selectedRows.map(row => row.id);
                if (ids.length > 0) {
                    ids.forEach(function(id) {
                        $.ajax({
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('copy-cardObject') }}",
                            data: {id: id},
                            success: function (response) {
                                // Обновить таблицу после успешного создания копии карточки объекта
                                refreshTable();
                            },
                            error: function (error) {
                                  console.log(error);
                            }
                        });
                    });
                }
            });



        });
    </script>
@endsection

