{{--страница реестр заказ-нарядов --}}
@extends('layouts.app')

@section('content')
    <div class="container" data-title="Работа с реестром заказ-нарядов TPM" data-step="25"
         data-intro="Здесь представлена таблица, содержащая в себе все заказ-наряды TPM, добавленные в систему.">
        <div class="reestrObject">
            <div class="reestrObject__btns d-flex mb-5">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary refreshTable" data-toggle="tooltip"
                            title="показать последние данные"
                            data-title="Работа с реестром заказ-нарядов TPM" data-step="26"
                            data-intro="По нажатию на данную кнопку обновляются данные в реестре в зависимости от внесенных свежих данных в систему.">
                        Обновить реестр
                    </button>
                    <button type="button" class="btn btn-primary" id="togglePeriodSelection" data-toggle="tooltip" title="показать записи за период"
                            data-title="Работа с реестром заказ-нарядов TPM" data-step="27"
                            data-intro="Обновлять данные в реестре в зависимости от выбранного периода и «Плановой даты» заказ-наряда.">
                        Выбрать период</button>
                    <button id="showActiveBtn" type="button" class="btn btn-success" data-toggle="tooltip" title="статус 'в работе'"
                            data-title="Работа с реестром заказ-нарядов TPM" data-step="28"
                            data-intro="Отображать только те заказы, в карточке которых указан статус «В работе»">
                        Показать активные заказ-наряды</button>
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
                        <div id="toolbar" data-title="Работа с реестром календарей" data-step="29"
                             data-intro="Это панель инструментов для таблицы. При выборе галочками одной или нескольких строк в реестре можно удалить записи из реестра, нажав соответствующую кнопку.
                             В строке поиска можно найти нужную запись по любой имеющейся информации. Информация об остальных кнопках справа на панели доступна после завершения обучения при наведении на них.">
                            <button id="remove" class="btn btn-danger" disabled>
                                <i class="fa fa-trash"></i> Удалить
                            </button>
                        </div>
                        <table id="reestrWorkOrder" data-url="/get-work-orders"
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
        document.addEventListener('DOMContentLoaded', function () {
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
            function getObjectsFromServer() {
                return $.get('/get-work-orders'); // Возвращаем Promise
            }
            function responseHandler(res) {
                $.each(res.rows, function (i, row) {
                    row.state = $.inArray(row.id, selections) !== -1;
                });
                return res;
            }
            // ------------------------------------ Функция для обновления таблицы ------------------------------------
            // Функция для обновления данных в таблице с учетом фильтрации по столбцу "deleted"
            function refreshTable() {
                getObjectsFromServer().done(function(data) {
                    // Фильтруем записи с deleted = 0
                    let filteredData = data.filter(function(item) {
                        return item.deleted !== 1;
                    });
                    initTable(filteredData); // Инициализируем таблицу с новыми данными
                });
            }

            $('.refreshTable').click(function () {
                refreshTable();
            });

            function detailFormatter(index, row) {
                let html = [];
                $.each(row, function (key, value) {
                    html.push('<p><b>' + key + ':</b> ' + value + '</p>');
                });
                return html.join('');
            }

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
                            {colspan: 10, title: 'Заказ наряды', align: 'center'},
                            {colspan: 3, title: 'Ответственные', align: 'center'},
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
                                    return '<a href="/reestr-work-orders/card-work-order/' + row.id + '" target="_blank">' + value + '</a>';
                                }
                            },
                            {title: 'Инв./заводской номер', field: 'number', align: 'center'},
                            {title: 'Место установки', field: 'location', align: 'center'},
                            {title: 'Вид ближайшего обслуживания', field: 'service_type', align: 'center'},
                            {title: 'Плановая дата обслуживания', field: 'planned_maintenance_date', align: 'center',
                                formatter: function(value, row) {
                                    if (value === null) {
                                        return null;
                                    } else {
                                        let date = new Date(value);
                                        if (isNaN(date)) {
                                            return '-'; // Если дата некорректна
                                        } else {
                                            return date.toLocaleDateString('ru-RU');
                                        }
                                    }
                                }
                            },
                            {title: 'Фактическая дата предыдущего обслуживания', field: 'prev_maintenance_date', align: 'center',
                                formatter: function(value, row) {
                                    if (value === null) {
                                        return null;
                                    } else {
                                        let date = new Date(value);
                                        if (isNaN(date)) {
                                            return '-'; // Если дата некорректна
                                        } else {
                                            return date.toLocaleDateString('ru-RU');
                                        }
                                    }
                                }
                            },
                            {
                                title: 'Статус заказ-наряда',
                                field: 'status',
                                align: 'center',
                                formatter: function(value, row) {
                                    if (value === 'В работе') {
                                        return '<span class="status-in-progress">' + value + '</span>';
                                    } else if (value === 'Выполнен') {
                                        return '<span class="status-completed">' + value + '</span>';
                                    } else {
                                        return value;
                                    }
                                }
                            },

                            {title: 'Дата создания', field: 'date_create', align: 'center',
                                formatter: function(value, row) {
                                    if (value === null) {
                                        return null;
                                    } else {
                                        let date = new Date(value);
                                        if (isNaN(date)) {
                                            return '-'; // Если дата некорректна
                                        } else {
                                            return date.toLocaleDateString('ru-RU');
                                        }
                                    }
                                }
                            },
                            // {title: 'Дата последнего сохранения', field: 'date_last_save', align: 'center'},
                            {title: 'Исполнитель', field: 'performer', align: 'center'},
                            {title: 'Ответственный', field: 'responsible', align: 'center'},
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

                $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
                    $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
                    selections = getIdSelections();
                });

                $remove.click(function () {
                    let ids = getIdSelections();
                    if (ids.length > 0) {
                        showConfirmDeleteModal();
                    }
                });

                //  ------------------------------------ Функция для отображения модального окна удаления ------------------------------------
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
                        url: "{{ route('delete-cardWorkOrder') }}",
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

            }
            //Вызов функции для получения данных с сервера
            getObjectsFromServer().done(function(data) {
                initTable(data); // Инициализируем таблицу с новыми данными
            });

            $(function () {
                initTable();
                $('#locale').change(initTable);
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
                    return row.status === 'В работе';
                });
                $table.bootstrapTable('load', activeObjects);
                isActiveFilter = true; // Устанавливаем флаг фильтрации в активное состояние
            }
            // Функция для сброса фильтрации и отображения всех объектов
            function resetFilter() {
                refreshTable(); // Перезагружаем таблицу, чтобы сбросить фильтр
                isActiveFilter = false; // Устанавливаем флаг фильтрации в неактивное состояние
            }


            // ------------------------------------ Показать за выбранный период ------------------------------------
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

                // Преобразуем даты в объекты Date
                const start = new Date(startDate);
                const end = new Date(endDate);
                // Фильтруем записи по выбранному периоду
                let data = $table.bootstrapTable('getData');
                let filteredData = data.filter(function (row) {
                    // Преобразуем плановую дату обслуживания в объект Date
                    const plannedDate = new Date(row.planned_maintenance_date);
                    // Проверяем, попадает ли плановая дата в выбранный период
                    return plannedDate >= start && plannedDate <= end;
                });
                // Обновляем таблицу, отображая только записи из отфильтрованных данных
                $table.bootstrapTable('load', filteredData);

                // Отобразить выбранный период под блоком с кнопками
                selectedPeriod.innerHTML = `
                <div class="alert alert-info" role="alert">
                    Выбранный период: с ${endDate.split('-').reverse().join('-')} по ${startDate.split('-').reverse().join('-')}
                    <button type="button" class="btn btn-danger ms-3" id="resetPeriodButton">Сбросить период</button>
                </div>`;
                // Добавляем обработчик клика на кнопку "Сбросить период"
                document.getElementById('resetPeriodButton').addEventListener('click', function () {
                    selectedPeriod.innerHTML = '';
                    refreshTable(); // После сброса периода обновляем таблицу, чтобы отобразить все записи
                });
            });

        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Получаем блок header на странице home
            var header = document.querySelector('header');
            var navbarNavElements = document.querySelectorAll('header ul.navbar-nav');
            var navElements = document.querySelectorAll('header ul.nav');
            var restartTutorialBtn = document.querySelector('#restartTutorialBtn');
            restartTutorialBtn.setAttribute('data-step', null);
            // Устанавливаем атрибут data-step равным null для header на странице home
            if (header) {
                header.setAttribute('data-step', null);
            }
            // Устанавливаем атрибут data-step равным null для всех элементов navbar-nav
            navbarNavElements.forEach(function(navbarNavElement) {
                navbarNavElement.setAttribute('data-step', null);
            });
            // Устанавливаем атрибут data-step равным null для всех элементов nav
            navElements.forEach(function(navElement) {
                navElement.setAttribute('data-step', null);
            });
        });
    </script>
@endsection
