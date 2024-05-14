{{--страница карточка заказ-наряда --}}
@extends('layouts.app')

@section('content')
    <div class="container custom_tab_style1_outer">
        <div class="row">
            {{-- ЗАГОЛОВОК С ПАНЕЛЬЮ КНОПОК --}}
            <div class="col-md-12 text-left">
                <h2 class="mb-4"><strong>Карточка заказ-наряда №{{$workOrder->number}} объекта "{{$cardObjectMain->name}}"</strong></h2>
            </div>
            <div class="btns d-flex mb-5">
                <div class="d-flex gap-2">
                    {{-- <button type="button" class="btn btn-success">Сохранить</button>--}}
                    <button type="button" class="btn btn-secondary me-5">Закрыть</button>

                    <a href="{{ route('downloadPDF', ['id' => $workOrder->id]) }}" target="_blank" class="btn btn-success">Выгрузить PDF</a>
{{--                    <a href="{{ route('downloadWordDocument', ['id' => $workOrder->id]) }}" target="_blank" class="btn btn-success">Выгрузить Word</a>--}}

                    <a href="/home/card-object/{{$cardObjectMain->id}}" target="_blank" type="button" class="btn btn-primary me-5">Открыть карточку объекта</a>

                    {{-- <button type="button" class="btn btn-outline-danger">Редактировать</button>--}}
                </div>
            </div>

            {{-- КАРТОЧКА С ВКЛАДКАМИ --}}
            <ul class="nav nav-tabs custom_tab_style1" id="carObjectTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="main-tab" data-bs-toggle="tab" data-bs-target="#main"
                            type="button" role="tab" aria-controls="main" aria-selected="false">ОСНОВНАЯ
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="carObjectTabContent">
                {{-- ВКЛАДКА "ОСНОВНАЯ" --}}
                <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
                    <div id="service__blocks" class="d-grid">
                        {{-- ОБЩИЕ ДАННЫЕ --}}
                        <div class="member_card_style general">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Общие данные</h4>
                                    <button class="btn btn-primary end_workOrder{{ $workOrder->status === 'Выполнен' ? ' disabled' : '' }}">
                                        Завершить заказ</button>
                                </div>
                                <div class="member-info--inputs d-flex gap-5">
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Вид инфраструктуры</label>
                                            <input name="infrastructure" class="form-control w-100" value="{{ $cardObjectMain->infrastructure }}" readonly
                                                   data-toggle="tooltip" title="изменить можно в карточке объекта 'основная'">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Наименование объекта</label>
                                            <input name="name" class="form-control w-100" value="{{ $cardObjectMain->name }}" readonly
                                                   data-toggle="tooltip" title="изменить можно в карточке объекта 'основная'">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Инв./заводской №</label>
                                            <input name="number" class="form-control w-100" value="{{ $cardObjectMain->number }}" readonly
                                                   data-toggle="tooltip" title="изменить можно в карточке объекта 'основная'">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Место установки</label>
                                            <input name="location" class="form-control w-100" value="{{ $cardObjectMain->location }}" readonly
                                                   data-toggle="tooltip" title="изменить можно в карточке объекта 'основная'">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Вид обслуживания</label>
                                            <input name="service_type" class="form-control w-100" value="{{ $cardObjectServices->service_type }}" readonly
                                                   data-toggle="tooltip" title="изменить можно в карточке объекта 'обслуживание'">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Плановая дата обслуживания</label>
                                            <input name="planned_maintenance_date" class="form-control w-100" value="{{ $cardObjectServices->planned_maintenance_date }}" readonly
                                                   data-toggle="tooltip" title="изменить можно в карточке объекта 'обслуживание'">
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата создания</label>
                                            <input name="date_create" class="form-control w-100" value="{{ $workOrder->date_create }}" readonly
                                                   data-toggle="tooltip" title="дата создания заказ-наряда">
                                        </div>
{{--                                        <div class="d-flex justify-content-between align-items-center gap-3">--}}
{{--                                            <label class="w-100">Дата последнего сохранения</label>--}}
{{--                                            <input name="date_last_save" class="form-control w-100" value="{{ $workOrder->date_last_save }}" readonly>--}}
{{--                                        </div>--}}
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Фактическая дата</label>
                                            @if ($workOrder && $workOrder->date_fact)
                                            <input name="date_fact" class="form-control w-100" value="{{ $workOrder->date_fact }}" readonly
                                                   data-toggle="tooltip" title="дата завершения заказ-наряда">
                                            @else
                                                <input name="date_fact" class="form-control w-100" value="дата завершения заказа"
                                                  readonly style="opacity: 0.5;" data-toggle="tooltip" title="дата появится после завершения заказ-наряда">
                                            @endif
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Исполнитель</label>
                                            <input name="performer" class="form-control w-100" value="{{ $cardObjectServices->performer }}" readonly
                                                   data-toggle="tooltip" title="изменить можно в карточке объекта 'обслуживание'">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Ответственный</label>
                                            <input name="responsible" class="form-control w-100" value="{{ $cardObjectServices->responsible }}" readonly
                                                   data-toggle="tooltip" title="изменить можно в карточке объекта 'обслуживание'">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Статус</label>
                                            <input name="status" class="form-control w-100" value="{{ $workOrder->status }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ВИДЫ РАБОТ --}}
                        <div class="member_card_style types">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Виды работ</h4>
                                </div>
                                <div class="typesOfWork">
                                    <!-- Используем класс row для создания строки -->
                                    <div class="grid-container">
                                        <!-- Используем класс col-md-6 для создания двух столбцов на широких экранах -->
                                        @foreach ($serviceTypes as $type)
                                            <div class="grid-item">
                                                <div class="form-check d-flex align-items-center gap-2">
                                                    @php
                                                        $title = $type->checked ? 'снять отметку выполненное' : 'отметить как выполненное';
                                                    @endphp
                                                    <input type="checkbox" class="form-check-input type-checkbox"
                                                           id="type_{{ $type->id }}" data-id="{{ $type->id }}"
                                                           {{ $type->checked ? 'checked' : '' }}
                                                           data-toggle="tooltip" title="{{ $title }}">
                                                    <label class="form-check-label" for="type_{{ $type->id }}">
                                                        {{ $type->type_work }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <script>
                                        $('.type-checkbox').on('change', function () {
                                            var typeId = $(this).data('id');
                                            var isChecked = $(this).is(':checked');

                                            $.ajax({
                                                type: "POST",
                                                headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                },
                                                url: "{{ route('update-type-checked') }}",
                                                data: {
                                                    type_id: typeId,
                                                    checked: isChecked ? 1 : 0 // Преобразуем в целое число для сохранения в MongoDB
                                                },
                                                success: function (response) {
                                                    console.log(response);
                                                },
                                                error: function (error) {
                                                    console.log(error);
                                                }
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                        {{-- РАСХОДНЫЕ МАТЕРИАЛЫ --}}
                        <div class="member_card_style materials">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Расходные материалы и ЗИП</h4>
                                </div>
                                <div class="material_text w-100">
                                    <!-- Используем значение $cardObjectServices->consumable_materials для отображения данных о расходных материалах -->
                                    <textarea class="form-control" readonly  data-toggle="tooltip" title="изменить можно в карточке объекта 'основная'"
                                    >{{ $cardObjectServices->consumable_materials }}</textarea>
                                </div>
                            </div>
                        </div>
                        {{-- ИЗОБРАЖЕНИЕ --}}
                        <div class="member_card_style image">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Изображение объекта</h4>
                                </div>
                                <div class="objectImage">
                                    @if ($cardObjectMain && $cardObjectMain->image)
                                        <!-- Если у объекта есть изображение, отобразите его -->
                                        <img src="{{ route('getImage', ['id' => $cardObjectMain->id]) }}" alt="Image">
                                    @else
                                        <!-- Если у объекта нет изображения, отобразите сообщение -->
                                        <p>Нет доступных изображений</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Модальное окно подтверждения завершения заказа-наряда -->
        <div class="modal fade" id="confirmEndWorkOrderModal" tabindex="-1" aria-labelledby="confirmEndWorkOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmEndWorkOrderModalLabel">Подтверждение завершения заказа-наряда</h5>
                        <button type="button" class="btn-close" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Вы уверены, что хотите завершить данный заказ-наряд?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="button" class="btn btn-danger" id="confirmEndWorkOrderButton">Завершить</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Обработчик события нажатия на кнопку "Завершить заказ"
            $('.end_workOrder').click(function () {
                // Открываем модальное окно с вопросом о завершении заказа-наряда
                $('#confirmEndWorkOrderModal').modal('show');
            });
            // Обработчик события нажатия на кнопку "Да" в модальном окне подтверждения
            $('#confirmEndWorkOrderButton').click(function () {
                // Устанавливаем текущую дату в поле "Фактическая дата"
                const currentDate = new Date();
                const formattedDate = currentDate.toLocaleDateString('ru-RU').split('.').reverse().join('-'); // Форматируем дату в формат dd-mm-yyyy
                $('input[name="date_fact"]').val(formattedDate);
                // Меняем статус на "Выполнен"
                $('input[name="status"]').val('Выполнен');
                // Отправляем данные в контроллер для сохранения изменений в базе данных
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('endWorkOrder') }}",
                    data: {
                        id: "{{ $workOrder->_id }}", // Здесь нужно передать ID текущего заказа-наряда
                        date_fact: formattedDate, // Передаем текущую дату
                        status: 'Выполнен' // Устанавливаем статус "Выполнен"
                    },
                    success: function (response) {
                        // Обработка успешного завершения запроса
                        console.log(response);
                    },
                    error: function (error) {
                        // Обработка ошибки
                        console.log(error);
                    }
                });
                // Закрываем модальное окно подтверждения
                $('#confirmEndWorkOrderModal').modal('hide');
            });
        </script>

        <script>
            $(document).ready(function () {
                $("#carObjectTab").show;
            });
        </script>
@endsection
