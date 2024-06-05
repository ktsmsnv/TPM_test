@extends('layouts.app')

@section('content')
    <div class="container" data-title="Работа с реестром графиков" data-step="14"
         data-intro="Здесь представлена таблица, содержащая в себе все графики, добавленные в систему.">
        <div class="reestrGraph">
            <div class="reestrGraph__btns d-flex justify-content-between">
                <button type="button" class="btn btn-secondary refreshTable" data-toggle="tooltip"
                        title="показать последние данные"
                        data-title="Работа с реестром графиков" data-step="15"
                        data-intro="По нажатию на данную кнопку обновляются данные в реестре в зависимости от внесенных свежих данных в систему.">
                    Обновить реестр
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success"
                            id="togglePeriodSelection" data-title="Работа с реестром графиков" data-step="16"
                            data-intro="Обновлять данные в реестре в зависимости от выбранного периода и «Года действия» графика.">
                        Выбрать период действия</button>
                    <button type="button" class="btn btn-success"
                            id="showActiveBtn" data-title="Работа с реестром графиков" data-step="17" data-toggle="tooltip"
                            title="Отображать только те графики, в карточке которых не заполнена «Дата архивации»"
                            data-intro="Кнопка «Показать активные графики» - отображать только те календари, в карточке которых не заполнена «Дата архивации».">
                        Показать активные графики</button>
                </div>
            </div>
            <div class="collapse" id="periodSelection">
                <div class="card card-body position-absolute" style="top: 50px;left: 1350px;z-index: 99;">
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
            <h3 class="text-center mb-4"><strong>Реестр графиков</strong></h3>
            <div class="card">
                <div class="card-body">
                    <div class="reestrGraph__table text-center">
                        <div id="toolbar" data-title="Работа с реестром объектов" data-step="18"
                             data-intro="Это панель инструментов для таблицы. При выборе галочками одной или нескольких строк в реестре можно удалить записи из реестра, нажав соответствующую кнопку.
                             В строке поиска можно найти нужную запись по любой имеющейся информации. Информация об остальных кнопках справа на панели доступна после завершения обучения при наведении на них.">
                            <button id="remove" class="btn btn-danger" disabled>
                                <i class="fa fa-trash"></i> Удалить
                            </button>
                        </div>
                        <table id="reestrGraph" data-url="/get-cardGraph"
                               data-toolbar="#toolbar" data-search="true"
                               data-show-refresh="true" data-show-toggle="true" data-show-fullscreen="true"
                               data-show-columns="true" data-show-columns-toggle-all="true"
                               data-show-export="true" data-click-to-select="true" data-minimum-count-columns="11"
                               data-show-pagination-switch="true" data-pagination="true"
                               data-id-field="id" data-response-handler="responseHandler">
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
                                    if (value === null) {
                                        return null;
                                    }
                                    else {
                                        // Преобразование даты в нужный формат (день-месяц-год)
                                        return new Date(value).toLocaleDateString('ru-RU');
                                    }
                                }
                            },
                            {
                                title: 'Дата последнего сохранения', field: 'date_last_save', align: 'center',
                                formatter: function (value, row) {
                                    if (value === null) {
                                        return null;
                                    }
                                    else {
                                        // Преобразование даты в нужный формат (день-месяц-год)
                                        return new Date(value).toLocaleDateString('ru-RU');
                                    }
                                }
                            },
                            {
                                title: 'Дата архивации', field: 'date_archive', align: 'center',
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
                            {title: 'Исполнитель', field: 'performer', align: 'center', visible: false,
                                formatter: function(value, row) {
                                    let performers = []; // Создаем пустой массив для всех исполнителей
                                    if (row.services && Array.isArray(row.services) && row.services.length > 0) {
                                        row.services.forEach(function(service) {
                                            performers.push(service.performer); // Добавляем исполнителя в массив
                                        });
                                        return performers.length > 0 ? performers : 'Нет исполнителя'; // Возвращаем массив всех исполнителей
                                    } else {
                                        return 'Нет исполнителя';
                                    }
                                }
                            },
                            {title: 'Ответственный', field: 'responsible', align: 'center', visible: false,
                                formatter: function(value, row) {
                                    let responsibles = []; // Создаем пустой массив для всех ответственных
                                    if (row.services && Array.isArray(row.services) && row.services.length > 0) {
                                        row.services.forEach(function(service) {
                                            responsibles.push(service.responsible); // Добавляем ответственного в массив
                                        });
                                        return responsibles.length > 0 ? responsibles : 'Нет ответственного'; // Возвращаем массив всех ответственных
                                    } else {
                                        return 'Нет ответственного';
                                    }
                                }
                            },
                            {title: 'Куратор', field: 'curator', align: 'center',
                                formatter: function(value, row) {
                                    let curators = []; // Создаем пустой массив для всех ответственных
                                    if (row.objects && Array.isArray(row.objects) && row.objects.length > 0) {
                                        row.objects.forEach(function(object) {
                                            curators.push(object.curator); // Добавляем ответственного в массив
                                        });
                                        return curators.length > 0 ? curators : 'Нет куратора'; // Возвращаем массив всех ответственных
                                    } else {
                                        console.log('Кураторы',  curators);
                                        return 'Нет куратора';
                                    }
                                }
                            },
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

            $(function () {
                initTable();
                $('#locale').change(initTable);
            });

            // $(function () {
            //     initTable();
            //     $('#locale').change(initTable);
            // });


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
                    return row.date_archive === null;
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
            var restartTutorialBtn = document.querySelector('#restartTutorialBtn');
            var navbarNavElements = document.querySelectorAll('header ul.navbar-nav');
            var navElements = document.querySelectorAll('header ul.nav');
            // Устанавливаем атрибут data-step равным null для header на странице home
            if (header) {
                header.setAttribute('data-step', null);
            }
            restartTutorialBtn.setAttribute('data-step', null);
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
