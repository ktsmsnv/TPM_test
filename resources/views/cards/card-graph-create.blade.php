{{--страница карточка графа --}}
@extends('layouts.app')
@section('content')
    @if(isset($error))
        <div class="alert alert-warning">
            {{ $error }}
            @if(isset($existingGraphs))
                <ul>
                    @foreach($existingGraphs as $graph)
                        <li><a target="_blank" href="{{ $graph['link'] }}">{{ $graph['name'] }}</a></li>
                    @endforeach
                </ul>
            @endif
            <a href="/home" type="button" class="btn btn-secondary me-5">Закрыть</a>
        </div>
    @else
    <div class="container custom_tab_style1_outer">
        <select class="form-control d-none" id="locale">
            <option value="ru-RU">ru-RU</option>
        </select>
        <div class="row">
            {{-- ЗАГОЛОВОК С ПАНЕЛЬЮ КНОПОК --}}
            <div class="col-md-12 text-left">
                <h2 class="mb-4"><strong>Создание карточки графика: "{{ $nameGraph }}"</strong></h2>
            </div>
            <div class="btns d-flex mb-5">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success saveCardGraph">Сохранить</button>
                    <a href="/home" type="button" class="btn btn-secondary me-5">Закрыть</a>
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
                                    <input type="hidden" name="cards_ids" value="{{ implode(',', $selectedIds) }}">
                                </div>
                                <div class="member-info--inputs d-flex gap-5">
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Наименование объекта</label>
                                            <input name="name" value="{{ $nameGraph }}" class="form-control w-100" readonly>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Вид инфраструктуры</label>
                                            <input name="infrastructure" value="{{ $selectedObjectMain->first()->infrastructure }}"
                                                   class="form-control w-100" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Куратор</label>
                                            <input name="curator" class="form-control w-100"
                                                   placeholder="Введите куратора">
                                            {{--                                            <input name="curator" value="{{ $selectedObjectMain->first()->curator }}"--}}
{{--                                                   class="form-control w-100" readonly>--}}
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Год действия</label>
                                            <input class="form-control w-100" name="year_action"
                                                   placeholder="Введите год действия">
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата создания</label>
                                            <input type="date" class="form-control w-100" name="date_create"
                                                   placeholder="Дата создания" value="{{ date('Y-m-d') }}"
                                                   data-toggle="tooltip" style="opacity: 0.5;" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата последнего сохранения</label>
                                            <input type="" class="form-control w-100" name="date_last_save"
                                                   data-toggle="tooltip" title="Дата последнего сохранения появляется и меняется автоматически, после сохранения"
                                                   placeholder="Дата последнего сохранения" readonly style="opacity: 0.5;">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата архивации</label>
                                            <input type="" class="form-control w-100" name="date_archive" data-toggle="tooltip"
                                                   title="Дата архивации появится после нажатия на кнопку архивация в карточке графика"
                                                   placeholder="Дата архивации" readonly style="opacity: 0.5;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ГРАФИК TPM --}}
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
                                           data-show-toggle="true"
                                           data-show-columns="true"
                                           data-show-columns-toggle-all="true"
                                           data-detail-view="true"
                                           data-show-export="true"
                                           data-click-to-select="true"
                                           data-detail-formatter="detailFormatter"
                                           data-minimum-count-columns="2"
                                           data-id-field="id"
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
{{--                                                @foreach ($object->services as $service)--}}
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
                                                                $maintenanceTypes = [];
                                                                foreach ($object->services as $service) {
                                                                    $plannedMaintenanceDate = $service->planned_maintenance_date;
                                                                    $service_type = $service->service_type;
                                                                    $month = date('n', strtotime($plannedMaintenanceDate));
                                                                    if ($month == $i) {
                                                                        $maintenanceExists = true;
                                                                        // Найти соответствующий тип обслуживания в массиве $maintenance
                                                                            foreach ($maintenance as $item) {
                                                                                if ($service->service_type == $item['service_type']) {
                                                                                    $maintenanceTypes[] = $item['short_name'];
                                                                                    break;
                                                                                }
                                                                            }
                                                                        }
                                                                }
                                                                echo implode(', ', $maintenanceTypes);
                                                            @endphp
                                                        </td>
                                                    @endfor
                                                </tr>
                                                @php $rowIndex++; @endphp
{{--                                            @endforeach --}}
                                            @endforeach
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
            document.addEventListener('DOMContentLoaded', function () {
            // $(document).ready(function () {
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

                var currentDate = new Date().toISOString().split('T')[0];


                $(".saveCardGraph").click(function () {
                    let formData = new FormData();
                    // Собираем данные с основной формы
                    formData.append('name', $("input[name=name]").val());
                    formData.append('infrastructure_type', $("input[name=infrastructure]").val());
                    formData.append('cards_ids', $("input[name=cards_ids]").val());
                    formData.append('curator', $("input[name=curator]").val());
                    formData.append('year_action', $("input[name=year_action]").val());
                    formData.append('date_create', $("input[name=date_create]").val());
                    formData.append('date_last_save', currentDate);
                    formData.append('date_archive', $("input[name=date_archive]").val());

                    // Отправляем данные на сервер
                    $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "/save-cardGraph-data/{{ $object->id }}",
                        data: formData,
                        processData: false, // Не обрабатывать данные
                        contentType: false, // Не устанавливать тип содержимого
                        success: function (response) {
                            // Обработка успешного ответа от сервера (например, отображение сообщения об успешном сохранении)
                            // alert("Данные успешно сохранены!");
                            console.log(formData);
                            window.location.href = "/pageReestrGraph";
                        },
                        error: function (error) {
                            // Обработка ошибки при сохранении данных
                            alert("Ошибка при сохранении данных!");
                            console.log(formData);
                        }
                    });
                });
            });
        </script>
    @endif
@endsection
