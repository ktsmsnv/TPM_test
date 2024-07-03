{{--страница реестр объектов (главная) --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="reestrObject" data-title="Работа с реестром объектов" data-step="5"
             data-intro="Здесь представлена таблица, содержащая в себе все объекты, зарегистрированные в системе.">
            <div class="reestrObject__btns d-flex justify-content-between mb-5">
                <button type="button" class="btn btn-secondary refreshTable" data-toggle="tooltip" title="показать последние данные"
                        data-title="Работа с реестром объектов" data-step="6"
                        data-intro="По нажатию на данную кнопку обновляются данные в реестре в зависимости от внесенных свежих данных в систему.">
                    Обновить реестр
                </button>
                <div class="d-flex gap-2">
                    <a id="showActiveBtn" type="button" class="btn btn-success" data-toggle="tooltip"
                       title="с датой вывода объекта из эксплуатации"
                       data-title="Работа с реестром объектов" data-step="7"
                       data-intro="По нажатию на данную кнопку отображаются только те объекты, в карточке которых заполнена «Дата вывода объекта из эксплуатации».">
                        Показать активные объекты</a>
                    <button target="_blank" type="button" class="btn btn-primary createCardObjectMain"
                       data-title="Работа с реестром объектов" data-step="8"
                       data-intro="По нажатию на данную кнопку создается новая сущность «Карточка объекта» и открывается в новом окне.">
                        Создать карточку объекта</button>
                    <a type="button" class="btn btn-primary btn-primary--2 copy_cardObject" disabled="true"
                       data-title="Работа с реестром объектов" data-step="9"
                       data-intro="Кнопка активна только при выборе галочками одной или нескольких строк в реестре. По нажатию создаются копии сущностей «Карточка объекта» и открываются в новом окне.">
                        Скопировать карточку объекта</a>
                    <button id="generateGraphTPM" class="btn btn-light" disabled
                            data-title="Работа с реестром объектов" data-step="10"
                            data-intro="Кнопка активна только при выборе галочками одной или нескольких строк в реестре с ОДИНАКОВЫМ  видом инфраструктуры. По нажатию создается новая сущность «График TPM» и открывается в отдельном окне.">
                        Сформировать график TPM</button>
                    <button class="btn btn-light createCalendar" disabled
                            data-title="Работа с реестром объектов" data-step="11"
                            data-intro="Кнопка активна только при выборе галочками одной или нескольких строк в реестре, а также при отсутствии уже созданного календаря на выбранные объекты. По нажатию создаются новые сущности «Календарь TPM» и открываются отдельном окне.">
                        Сформировать календарь TPM</button>
                    <button class="btn btn-light create_workOrder" disabled
                            data-title="Работа с реестром объектов" data-step="12"
                            data-intro="Кнопка активна только при выборе галочками одной или нескольких строк в реестре, а также при отсутствии уже созданного заказа на выбранный объект. По нажатию создаются новые сущности «Заказ-наряд TPM»  и открываются отдельном окне.">
                        Сформировать заказ-наряд TPM</button>
                </div>
            </div>
            <select class="form-control d-none" id="locale">
                <option value="ru-RU">ru-RU</option>
            </select>
            <h3 class="text-center mb-4"><strong>Реестр объектов</strong></h3>
            <div class="card">
                <div class="card-body">
                    <div class="reestrObject__table text-center">
                        <div id="toolbar" data-title="Работа с реестром объектов" data-step="13"
                             data-intro="Это панель инструментов для таблицы. При выборе галочками одной или нескольких строк в реестре можно удалить записи из реестра, нажав соответствующую кнопку.
                             В строке поиска можно найти нужную запись по любой имеющейся информации. Информация об остальных кнопках справа на панели доступна после завершения обучения при наведении на них.">
                            <button id="remove" class="btn btn-danger" disabled>
                                <i class="fa fa-trash"></i> Удалить
                            </button>
                        </div>
                        <table id="reestrObject" data-url="/get-objects"
                               data-toolbar="#toolbar"  data-search="true"
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

    <!------- Модальное окно подтверждения удаления ------->
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

            let originalData = []; // Сохраняем исходные данные таблицы

            // ------------------------------------ выбор полей checked ------------------------------------
            function getIdSelections() {
                return $.map($table.bootstrapTable('getSelections'), function (row) {
                    return row.id
                });
            }


            // ------------------------------------ Функция для получения данных с сервера ------------------------------------
            function getObjectsFromServer() {
                return $.get('/get-objects'); // Возвращаем Promise
            }

            // ------------------------------------ Функция для обновления таблицы ------------------------------------
            function refreshTable() {
                getObjectsFromServer().done(function(data) {
                    initTable(data); // Инициализируем таблицу с новыми данными
                });
            }

            $('.refreshTable').click(function () {
                refreshTable();
            });
            // ------------------------------------ Функция для инициализации таблицы ------------------------------------
            function initTable(data) {
                console.log('Данные:', data);
                originalData = data;
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
                                    return '<a href="/home/card-object/' + row.id + '" target="_blank"' +
                                        'data-toggle="tooltip" title="открыть карточку объекта">' + value + '</a>';
                                }
                            },

                            {title: 'Дата ввода в эксплуатацию', field: 'date_usage', align: 'center',
                                formatter: function(value, row) {
                                    if (value === null) {
                                        return null;
                                    }
                                    else {
                                        // Преобразование даты в нужный формат (день-месяц-год)
                                        return new Date(value).toLocaleDateString('ru-RU');
                                    }
                                }
                            },
                            {title: 'Дата вывода из эксплуатации', field: 'date_usage_end', align: 'center',
                                formatter: function(value, row) {
                                    if (value === null) {
                                        return null;
                                    }
                                    else {
                                        // Преобразование даты в нужный формат (день-месяц-год)
                                        return new Date(value).toLocaleDateString('ru-RU');
                                    }
                                }},
                            {title: 'Дата окончания аттестации/гарантии', field: 'date_cert_end', align: 'center',
                                formatter: function(value, row) {
                                    if (value === null) {
                                        return null;
                                    }
                                    else {
                                        // Преобразование даты в нужный формат (день-месяц-год)
                                        return new Date(value).toLocaleDateString('ru-RU');
                                    }
                                }},
                            {title: 'Инв./заводской номер', field: 'number', align: 'center'},

                            {title: 'Место установки', field: 'location', align: 'center'},
                            {
                                title: 'Плановая дата обслуживания',
                                field: 'planned_maintenance_date',
                                align: 'center',
                                formatter: function(value, row) {
                                    // console.log('Services:', row.services);
                                    // console.log('Services length:', row.services.length);

                                    let nearestMaintenanceDate = null;
                                    if (row.services && Object.keys(row.services).length > 0) {
                                        Object.values(row.services).forEach(function(service) {
                                            if (!nearestMaintenanceDate || new Date(service.planned_maintenance_date) < new Date(nearestMaintenanceDate)) {
                                                nearestMaintenanceDate = service.planned_maintenance_date;
                                            }
                                        });
                                    }

                                    if (nearestMaintenanceDate) {
                                        let date = new Date(nearestMaintenanceDate);
                                        if (date instanceof Date && !isNaN(date)) {
                                            return date.toLocaleDateString('ru-RU');
                                        } else {
                                            return '-';
                                        }
                                    } else {
                                        return 'Нет запланированных обслуживаний';
                                    }
                                    //     return nearestMaintenanceDate ? new Date(nearestMaintenanceDate).toLocaleDateString('ru-RU') : 'Нет запланированных обслуживаний';
                                    // } else {
                                    //     return 'Нет запланированных обслуживаний';
                                    // }
                                }
                            },
                            {
                                title: 'Фактическая дата предыдущего обслуживания',
                                field: 'prev_maintenance_date',
                                align: 'center',
                                formatter: function(value, row) {
                                    let nearestService = null;
                                    if (row.services && Object.keys(row.services).length > 0) {
                                        Object.values(row.services).forEach(function(service) {
                                            if (!nearestService || new Date(service.planned_maintenance_date) < new Date(nearestService.planned_maintenance_date)) {
                                                nearestService = service;
                                            }
                                        });
                                    }

                                    if (nearestService && nearestService.prev_maintenance_date) {
                                        let date = new Date(nearestService.prev_maintenance_date);
                                        if (date instanceof Date && !isNaN(date)) {
                                            return date.toLocaleDateString('ru-RU');
                                        } else {
                                            return '-';
                                        }
                                    } else {
                                        return 'Нет даты предыдущего обслуживания';
                                    }
                                    //     return nearestService ? new Date(nearestService.prev_maintenance_date).toLocaleDateString('ru-RU') : 'Нет даты предыдущего обслуживания';
                                    // } else {
                                    //     return 'Нет даты предыдущего обслуживания';
                                    // }
                                }
                            },
                            {
                                title: 'Вид ближайшего обслуживания',
                                field: 'service_type',
                                align: 'center',
                                formatter: function(value, row) {
                                    let nearestService = null;
                                    if (row.services && Object.keys(row.services).length > 0) {
                                        Object.values(row.services).forEach(function(service) {
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
                                    if (row.services && Object.keys(row.services).length > 0) {
                                        Object.values(row.services).forEach(function(service) {
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
                                    if (row.services && Object.keys(row.services).length > 0) {
                                        Object.values(row.services).forEach(function(service) {
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
                                    if (row.services && Object.keys(row.services).length > 0) {
                                        let nearestService = null;
                                        Object.values(row.services).forEach(function(service) {
                                            if (!nearestService || new Date(service.planned_maintenance_date) < new Date(nearestService.planned_maintenance_date)) {
                                                nearestService = service;
                                            }
                                        });
                                        if (nearestService && nearestService.work_order) {
                                            return '<a href="' + nearestService.work_order + '" target="_blank" class="tool-tip" title="открыть карточку заказ-наряда">открыть</a>';
                                        } else {
                                            return 'Нет заказа-наряда';
                                        }
                                    } else {
                                        return 'Нет запланированных обслуживаний';
                                    }
                                }
                            },


                            {
                                title: 'Календарь TPM',
                                field: 'calendar',
                                align: 'center',
                                formatter: function(value, row) {
                                    if (row.calendar) {
                                        return row.calendar;
                                    } else {
                                        return 'Нет календаря';
                                    }
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

            // Функция для сброса всех сохраненных выбранных цветов
            function resetSelectedColors() {
                // console.log("Clearing selected colors from localStorage:");
                let keysToRemove = [];
                for (let i = 0; i < localStorage.length; i++) {
                    const key = localStorage.key(i);
                    if (key.endsWith('_selectedColor')) {
                        keysToRemove.push(key);
                    }
                }
                // Теперь удаляем все ключи, которые собрали
                for (let key of keysToRemove) {
                    // console.log("Removing:", key);
                    localStorage.removeItem(key);
                }
            }

            $(".createCardObjectMain").click(function () {
                // Сброс всех сохраненных выбранных цветов
                resetSelectedColors();

                // console.log(localStorage.length);

                // Убираем все выделенные цвета на UI
                $('.color-option').removeClass('selected');
                $('input[name="selectedColor"]').val('');


                // Перенаправляем пользователя на главную страницу
                window.location.href = "/home/card-object-create";
            });

            //Вызов функции для получения данных с сервера
            getObjectsFromServer().done(function(data) {
                initTable(data); // Инициализируем таблицу с новыми данными
            });

            // Вызов функции для обновления состояния кнопки при загрузке страницы
            updateCopyButtonState();
            $table.on('check.bs.table uncheck.bs.table ' + 'check-all.bs.table uncheck-all.bs.table',
                function () {
                    $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
                    $generateGraphTPM.prop('disabled', !$table.bootstrapTable('getSelections').length)
                    $('.create_workOrder').prop('disabled', !$table.bootstrapTable('getSelections').length)
                    $('.createCalendar').prop('disabled', !$table.bootstrapTable('getSelections').length)
                    selections = getIdSelections();
                    updateCopyButtonState();

                    let selectedRows = $table.bootstrapTable('getSelections');
                    if (selectedRows.length === 1) { // Проверяем, что выбрана только одна запись
                        let infrastructure = selectedRows[0].infrastructure; // Получаем вид инфраструктуры выбранной строки
                        filterTable(infrastructure); // Фильтруем таблицу по виду инфраструктуры выбранной карточки объекта
                    }
            });

            // // Изменяем обработчик события на изменение состояния чекбокса
            // $table.on('check.bs.table uncheck.bs.table' + 'check-all.bs.table uncheck-all.bs.table',
            //     function () {
            //     let selectedRows = $table.bootstrapTable('getSelections');
            //     if (selectedRows.length === 1) { // Проверяем, что выбрана только одна запись
            //         let infrastructure = selectedRows[0].infrastructure; // Получаем вид инфраструктуры выбранной строки
            //         filterTable(infrastructure); // Фильтруем таблицу по виду инфраструктуры выбранной карточки объекта
            //     }
            // });

            function filterTable(infrastructure) {
                let filteredData = originalData.filter(function (row) {
                    return row.infrastructure === infrastructure;
                });
                $table.bootstrapTable('load', filteredData);
            }

            // ------------------------------------ обработчик нажатия по кнопке удаления ------------------------------------
            $remove.click(function () {
                let ids = getIdSelections();
                if (ids.length > 0) {
                    showConfirmDeleteModal();
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


            $generateGraphTPM.click(function () {
                let ids = getIdSelections();
                console.log(ids);
                if (ids.length > 0) {
                    // Отправка запроса на сервер
                    $.ajax({
                        url: "/pageReestrGraph/card-graph-create",
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: { ids: ids.join(',') },
                        success: function (response) {
                            if (response.status === 'error') {
                                alert(response.message);
                            } else {
                                window.location.href = "/pageReestrGraph/card-graph-create?ids=" + ids.join(',');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Ошибка:', error);
                        }
                    });
                }
            });


            // ------------------------------------ Показать активные объекты ------------------------------------
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
                    return row.date_usage_end;
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
                $table.bootstrapTable('load', activeObjects);
                isActiveFilter = true; // Устанавливаем флаг фильтрации в активное состояние
            }
            // Функция для сброса фильтрации и отображения всех объектов
            function resetFilter() {
                // $table.bootstrapTable('load', originalData);
                refreshTable(); // Перезагружаем таблицу, чтобы сбросить фильтр
                isActiveFilter = false; // Устанавливаем флаг фильтрации в неактивное состояние
            }


            // ------------------------------------ Копирование карточки объекта ------------------------------------
            // Функция для обновления состояния кнопки "Скопировать карточку объекта"
            function updateCopyButtonState() {
                let selectedRows = $table.bootstrapTable('getSelections');
                let ids = selectedRows.map(row => row.id);
                if (ids.length > 0) {
                    $('.copy_cardObject').prop('disabled', false).removeAttr('data-toggle').attr('title', ''); // Активировать кнопку
                } else {
                    $('.copy_cardObject').prop('disabled', true).attr('data-toggle', 'tooltip').attr('title', 'Выберите объекты'); // Деактивировать кнопку
                }
            }

            // создание копии карточки объекта
            $('.copy_cardObject').click(function () {
                let selectedRows = $table.bootstrapTable('getSelections');
                let ids = selectedRows.map(row => row.id);
                if (ids.length > 0) {
                    let newTabs = []; // Массив для ссылок на новые вкладки
                    ids.forEach(function(id) {
                        $.ajax({
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('copy-cardObject') }}",
                            data: {id: id},
                            success: function (response) {
                                // Создать ссылку на новую вкладку
                                let newTab = window.open(response.url, '_blank');
                                newTabs.push(newTab); // Добавить ссылку в массив
                            },
                            error: function (error) {
                                console.log(error);
                            }
                        });
                    });
                    // После того как все запросы выполнены, активировать каждую вкладку
                    setTimeout(function() {
                        newTabs.forEach(function(tab) {
                            tab.focus(); // Переключить фокус на каждую вкладку
                        });
                    }, 1000); // Задержка, чтобы дождаться завершения всех запросов
                }
            });


            // ------------------------------------ создание заказ-наряда ------------------------------------
            $('.create_workOrder').click(function () {
                var selectedRows = $table.bootstrapTable('getSelections');
                var selectedIds = selectedRows.map(row => row.id);

                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('create-work-order') }}",
                    data: { selected_ids: selectedIds },
                    success: function (response) {
                        if (response.existingWorkOrders && response.existingWorkOrders.length > 0) {
                            var html = '<div class="container">' +
                                '<div class="alert alert-warning">' +
                                '<h4>Следующие заказ-наряды уже существуют:</h4>' +
                                '<ul>';

                            response.existingWorkOrders.forEach(function (workOrder) {
                                html += '<li><a target="_blank" href="' + workOrder.link + '">' + workOrder.name + '</a></li>';
                            });

                            html += '</ul>' +
                                '<a href="/home" type="button" class="btn btn-secondary me-5">Закрыть</a>' +
                                '</div></div>';

                            $('body').html(html);
                        } else {
                            response.results.forEach(function (result) {
                                if (result.status === 'success') {
                                    window.open(result.url, '_blank');
                                } else {
                                    alert(result.message);
                                }
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        alert("Произошла ошибка при выполнении запроса: " + xhr.responseText);
                    }
                });
            });




            $('.createCalendar').click(function () {
                let selectedRows = $table.bootstrapTable('getSelections');
                if (selectedRows.length > 0) {
                    selectedRows.forEach(function(row) {
                        // Создаем URL для каждой выбранной записи
                        let url = "/card-calendar-create/" + row.id;
                        // Открываем URL в новой вкладке
                        window.open(url, '_blank');
                    });
                }
            });

        });
    </script>
@endsection

