{{--страница карточка графа --}}
@extends('layouts.app')

@section('content')
    <div class="container custom_tab_style1_outer">
        <select class="form-control d-none" id="locale">
            <option value="ru-RU">ru-RU</option>
        </select>
        <div class="row">
            {{-- ЗАГОЛОВОК С ПАНЕЛЬЮ КНОПОК --}}
            <div class="col-md-12 text-left">
                <h1 class="mb-4"><strong>Карточка
                        графика: "{{ $data_CardObjectMain->name ?? 'Название объекта не найдено' }}"</strong></h1>
            </div>
            <div class="btns d-flex mb-5">
                <div class="d-flex gap-2">
                    <a href="/pageReestrGraph" type="button" class="btn btn-secondary me-5">Закрыть</a>
                    <button type="button" class="btn btn-success">Выгрузить PDF</button>
                    <a href="{{ route('cardGraph-edit', ['id' => $data_CardObjectMain->_id]) }}" target="_blank" type="button" class="btn btn-outline-danger">Редактировать</a>
                </div>
            </div>

            {{-- КАРТОЧКА С ВКЛАДКОЙ --}}
            <ul class="nav nav-tabs custom_tab_style1" id="cardGraphTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="mainCardGraph-tab" data-bs-toggle="tab" data-bs-target="#mainCardGraph"
                            type="button" role="tab" aria-controls="mainCardGraph" aria-selected="true">ОСНОВНАЯ
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="cardGraphTabContent">
                {{-- ВКЛАДКА "ОСНОВНАЯ" --}}
                <div class="tab-pane fade show active" id="mainCardGraph" role="tabpanel" aria-labelledby="mainCardGraph-tab">
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
                                            <input name="" placeholder="Введите вид инфраструктуры" class="form-control w-100"
                                                   readonly value="{{ $data_CardObjectMain->infrastructure ?? 'нет данных' }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Куратор</label>
                                            <input name="" placeholder="Введите куратора" class="form-control w-100"
                                                   readonly value="{{$data_CardObjectMain->graph->first()->curator ?? 'нет данных' }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Год действия</label>
                                            <input name="" placeholder="Введите год действия" class="form-control w-100"
                                                   readonly value="{{$data_CardObjectMain->graph->first()->year_action ?? 'нет данных' }}">
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата создания</label>
                                            <input name="" placeholder="Введите дату создания" class="form-control w-100"
                                                   readonly value="{{ date('d.m.Y', strtotime($data_CardObjectMain->graph->first()->date_create)) ?? 'нет данных'  }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата последнего сохранения</label>
                                            <input name="" placeholder="Введите дату последнего сохранения" class="form-control w-100"
                                                   readonly value="{{ date('d.m.Y', strtotime($data_CardObjectMain->graph->first()->date_last_save)) ?? 'нет данных'  }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата архивации</label>
                                            <input name="" placeholder="Введите дату архивации" class="form-control w-100"
                                                   readonly value="{{ date('d.m.Y', strtotime($data_CardObjectMain->graph->first()->date_archive)) ?? 'нет данных'  }}">
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
                                    <table id="reestrCardGraph"
                                           data-toolbar="#toolbar"
                                           data-search="true"
                                           data-show-refresh="true"
{{--                                           data-show-toggle="true"--}}
{{--                                           data-show-fullscreen="true"--}}
                                           data-show-columns="true"
                                           data-show-columns-toggle-all="true"
{{--                                           data-detail-view="true"--}}
                                           data-show-export="true"
                                           data-click-to-select="true"
                                           data-detail-formatter="detailFormatter"
                                           data-minimum-count-columns="2"
{{--                                           data-show-pagination-switch="true"--}}
{{--                                           data-pagination="true"--}}
                                           data-id-field="id"
{{--                                           data-show-footer="true"--}}
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
                                        @php $rowIndex = 1; @endphp
                                        @foreach($selectedObjectMain as $index => $object)
                                            @foreach ($object->services as $service)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $rowIndex }}</td>
                                                    <td>{{ $object->name }}</td>
                                                    <td>{{ $object->number }}</td>
                                                    @for($i = 1; $i <= 12; $i++)
                                                        <td>
                                                            @php
                                                                $maintenanceExists = false;
                                                                $maintenanceType = ' ';
//                                                                foreach ($object->services as $service) {
                                                                    $plannedMaintenanceDate = $service->planned_maintenance_date;
                                                                    $service_type = $service->service_type;
                                                                    $month = date('n', strtotime($plannedMaintenanceDate));
                                                                    if ($month == $i) {
                                                                        $maintenanceExists = true;
                                                                        // Найти соответствующий тип обслуживания в массиве $maintenance
                                                                        foreach ($maintenance as $item) {
                                                                            if ($service->service_type == $item['service_type']) {
                                                                                $maintenanceType = $item['short_name'];
                                                                                break;
                                                                            }
                                                                        }
//                                                                    }
                                                                }
                                                               echo $maintenanceExists ? $maintenanceType : ' ';
                                                            @endphp
                                                        </td>
                                                    @endfor
                                                </tr>
                                                @php $rowIndex++; @endphp
                                            @endforeach @endforeach
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

                let $table = $('#reestrCardGraph');
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
            });
        </script>
@endsection
