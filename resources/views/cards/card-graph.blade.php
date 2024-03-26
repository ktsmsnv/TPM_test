{{--страница карточка объекта --}}
@extends('layouts.app')

@section('content')
    <div class="container custom_tab_style1_outer">
        <select class="form-control d-none" id="locale">
            <option value="ru-RU">ru-RU</option>
        </select>
        <div class="row">
            {{-- ЗАГОЛОВОК С ПАНЕЛЬЮ КНОПОК --}}
            <div class="col-md-12 text-left">
                <h2 class="mb-4"><strong>Карточка графика</strong></h2>
            </div>
            <div class="btns d-flex mb-5">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success">Сохранить</button>
                    <button type="button" class="btn btn-secondary me-5">Закрыть</button>
                    <button type="button" class="btn btn-success">Выгрузить PDF</button>
                </div>
            </div>

            {{-- КАРТОЧКА С ВКЛАДКОЙ --}}
            <ul class="nav nav-tabs custom_tab_style1" id="cardGraphTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="main-tab" data-bs-toggle="tab" data-bs-target="#main"
                            type="button" role="tab" aria-controls="main" aria-selected="true">ОСНОВНАЯ
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="cardGraphTabContent">
                {{-- ВКЛАДКА "ОСНОВНАЯ" --}}
                <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
                    <div id="main__blocks" class="d-grid">
                        {{-- ОБЩИЕ ДАННЫЕ --}}
                        <div class="member_card_style general">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Общие данные</h4>
                                    <button class="btn btn-primary">Заархивировать</button>
                                </div>
                                <div class="member-info--inputs d-flex gap-5">
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Вид инфраструктуры</label>
                                            <input name="" placeholder="Введите вид инфраструктуры"
                                                   class="form-control w-100">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Куратор</label>
                                            <input name="" placeholder="Введите куратора"
                                                   class="form-control w-100">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Год действия</label>
                                            <input class="form-control w-100" name=""
                                                   placeholder="Введите год действия">
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата создания</label>
                                            <input class="form-control w-100" name=""
                                                   placeholder="Введите дату создания">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата последнего сохранения</label>
                                            <input class="form-control w-100" name=""
                                                   placeholder="Введите дату последнего сохранения">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата архивации</label>
                                            <input class="form-control w-100" name=""
                                                   placeholder="Введите дату архивации">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ДОКУМЕНТАЦИЯ --}}
                        <div class="member_card_style documentation">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>График ТРМ</h4>
                                </div>
                                <div class="objectDocs">
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
                                                <th colspan="3">Объекты инфраструктуры</th>
                                                <th colspan="12">Виды обслуживания</th>
                                            </tr>
                                            <tr>
                                                <th>№ п/п</th>
                                                <th>Наименование объекта</th>
                                                <th>Инв./заводской №</th>
                                                <th>Янв.</th>
                                                <th>Фев.</th>
                                                <th>Мар.</th>
                                                <th>Апр.</th>
                                                <th>Май</th>
                                                <th>Июн.</th>
                                                <th>Июл.</th>
                                                <th>Авг.</th>
                                                <th>Сен.</th>
                                                <th>Окт.</th>
                                                <th>Ноя.</th>
                                                <th>Дек.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr data-id="1">
                                                <td></td>
                                                <td>1</td>
                                                <td>Сварочное оборудование JASIC MIG 3500 TECH N222</td>
                                                <td>из карточки объекта</td>
                                                <td>РР</td>
                                                <td>РР</td>
                                                <td>РР</td>
                                                <td>ТО</td>
                                                <td>РР</td>
                                                <td>РР</td>
                                                <td>РР</td>
                                                <td>РР</td>
                                                <td>РР</td>
                                                <td>КР</td>
                                                <td>КР</td>
                                                <td>КР</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $("#cardGraphTab").show;

                // Получаем все блоки цветов
                const colorOptions = document.querySelectorAll('.color-option');
                // Добавляем обработчик события для каждого блока цвета
                colorOptions.forEach(option => {
                    option.addEventListener('click', () => {
                        // Убираем рамку у всех блоков цветов
                        colorOptions.forEach(opt => opt.classList.remove('selected'));
                        // Добавляем рамку только выбранному блоку цвета
                        option.classList.add('selected');
                        // Получаем цвет выбранного блока и устанавливаем его в скрытом поле ввода
                        const selectedColor = option.getAttribute('data-color');
                        document.getElementById('selectedColor').value = selectedColor;
                    });
                });


                let $table = $('#reestrObject');
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
                            { field: 'type', title: 'Объект инфраструктуры', align: 'center' },
                            { field: 'name', title: 'Виды обслуживания', align: 'center' },
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

                // // СКРИПТ ТАБЛИЦЫ
                // var $table = $('#cardGraphTable');
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
                //             {
                //                 field: 'id',
                //                 title: '№ п/п',
                //                 valign: 'middle',
                //                 sortable: true,
                //             },
                //             {
                //                 field: 'a',
                //                 title: 'Наименование объекта',
                //                 valign: 'middle',
                //                 sortable: true,
                //             },
                //             {
                //                 field: 's',
                //                 title: 'Инв./заводской №',
                //                 valign: 'middle',
                //                 sortable: true
                //             },
                //             {
                //                 field: 'd',
                //                 title: 'Янв.',
                //                 valign: 'middle',
                //                 sortable: true
                //             },
                //             {
                //                 field: 'f',
                //                 title: 'Фев.',
                //                 valign: 'middle',
                //                 sortable: true
                //             },
                //             {
                //                 field: 'g',
                //                 title: 'Мар.',
                //                 valign: 'middle',
                //                 sortable: true
                //             },
                //             {
                //                 field: 'h',
                //                 title: 'Апр.',
                //                 valign: 'middle',
                //                 sortable: true
                //             },
                //             {
                //                 field: 'j',
                //                 title: 'Май',
                //                 valign: 'middle',
                //                 sortable: true
                //             },
                //             {
                //                 field: 'k',
                //                 title: 'Июн.',
                //                 valign: 'middle',
                //                 sortable: true
                //             },
                //             {
                //                 field: 'l',
                //                 title: 'Июл.',
                //                 valign: 'middle',
                //                 sortable: true
                //             },
                //             {
                //                 field: 'z',
                //                 title: 'Авг.',
                //                 valign: 'middle',
                //                 sortable: true
                //             },
                //             {
                //                 field: 'x',
                //                 title: 'Сен.',
                //                 valign: 'middle',
                //                 sortable: true
                //             },
                //             {
                //                 field: 'c',
                //                 title: 'Окт.',
                //                 valign: 'middle',
                //                 sortable: true
                //             },
                //             {
                //                 field: 'v',
                //                 title: 'Ноя.',
                //                 valign: 'middle',
                //                 sortable: true
                //             },
                //             {
                //                 field: 'b',
                //                 title: 'Дек.',
                //                 valign: 'middle',
                //                 sortable: true
                //             }
                //         ]
                //     });
                // }
            });
        </script>
@endsection
