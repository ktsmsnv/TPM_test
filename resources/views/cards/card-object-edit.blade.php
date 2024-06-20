{{--страница РЕДАКТИРОВАНИЕ карточка объекта --}}
@extends('layouts.app')

@section('content')
    <div class="container custom_tab_style1_outer">
        <div class="row">
            {{-- ЗАГОЛОВОК С ПАНЕЛЬЮ КНОПОК --}}
            <div class="col-md-12 text-left">
            </div>
            <h1 class="mb-4"><strong>Редактирование карточки объекта "{{ $data_CardObjectMain->name ?? 'Название объекта не найдено' }}"</strong></h1>
        </div>
        <div class="btns d-flex mb-5">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-success saveEditObject">Сохранить изменения</button>
                <a href="{{ route('cardObject', ['id' => $data_CardObjectMain->_id]) }}" type="button" class="btn btn-secondary me-5">Отменить изменения</a>

                <a href="" type="button" class="btn btn-primary">Скопировать карточку объекта</a>

                <label for="imageUpload" class="btn btn-primary">Загрузить изображение</label>
                <input type="file" id="imageUpload" class="d-none" multiple accept="image/*">
            </div>
        </div>

        {{-- КАРТОЧКА С ВКЛАДКАМИ --}}
        <ul class="nav nav-tabs custom_tab_style1" id="cardObjectTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="main-tab" data-bs-toggle="tab" data-bs-target="#main"
                        type="button" role="tab" aria-controls="main" aria-selected="true">ОСНОВНАЯ
                </button>
            </li>
            @foreach ($data_CardObjectMain->services as $key => $service)
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if ($key === 0) @endif" id="service_{{ $key + 1 }}-tab" data-bs-toggle="tab"
                            data-bs-target="#service_{{ $key + 1 }}" type="button" role="tab" aria-controls="service_{{ $key + 1 }}"
                            aria-selected="{{ $key === 0 ? 'true' : 'false' }}">Обслуживание {{ $key + 1 }}</button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content" id="cardObjectTabContent">
            {{-- ВКЛАДКА "ОСНОВНАЯ" --}}
            <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
                <div id="main__blocks" class="d-grid">
                    {{-- ОБЩИЕ ДАННЫЕ --}}
                    <div class="member_card_style general">
                        <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Общие данные</h4>
                                    <button class="btn btn-primary createService">Создать обслуживание</button>
                                </div>
                            <div class="member-info--inputs d-flex gap-5">
                                <div class="d-flex flex-column gap-3 w-50">
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <label class="w-100">Вид инфраструктуры</label>
                                        <select class="form-select" name="infrastructure">
                                            <option value="" disabled selected>Выберите вид</option>
                                            <option value="Технологическая" {{ isset($data_CardObjectMain) && $data_CardObjectMain->infrastructure === "Технологическая" ? 'selected' : '' }}>Технологическая</option>
                                            <option value="Информационная" {{ isset($data_CardObjectMain) && $data_CardObjectMain->infrastructure === "Информационная" ? 'selected' : '' }}>Информационная</option>
                                            <option value="Бытовая" {{ isset($data_CardObjectMain) && $data_CardObjectMain->infrastructure === "Бытовая" ? 'selected' : '' }}>Бытовая</option>
                                            <option value="Инженерная" {{ isset($data_CardObjectMain) && $data_CardObjectMain->infrastructure === "Инженерная" ? 'selected' : '' }}>Инженерная</option>
                                            <option value="Электротехническая" {{ isset($data_CardObjectMain) && $data_CardObjectMain->infrastructure === "Электротехническая" ? 'selected' : '' }}>Электротехническая</option>
                                            <option value="Безопасность" {{ isset($data_CardObjectMain) && $data_CardObjectMain->infrastructure === "Безопасность" ? 'selected' : '' }}>Безопасность</option>
                                        </select>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <label class="w-100">Наименование объекта</label>
                                        <input name="name" class="form-control w-100"
                                               value="{{ $data_CardObjectMain->name ?? 'нет данных' }}">
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <label class="w-100">Инв./заводской №</label>
                                        <input class="form-control w-100" name="number"
                                               value="{{ $data_CardObjectMain->number ?? 'нет данных' }}">
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <label class="w-100">Место установки</label>
                                        <select class="form-select" name="location">
                                            <option value="" disabled selected>Выберите место</option>
                                            <option value="Участок ЭОБ" {{ isset($data_CardObjectMain) && $data_CardObjectMain->location === "Участок ЭОБ" ? 'selected' : '' }}>Участок ЭОБ</option>
                                            <option value="Участок сборки" {{ isset($data_CardObjectMain) && $data_CardObjectMain->location === "Участок сборки" ? 'selected' : '' }}>Участок сборки</option>
                                            <option value="БВЗ (1 этаж)" {{ isset($data_CardObjectMain) && $data_CardObjectMain->location === "БВЗ (1 этаж)" ? 'selected' : '' }}>БВЗ (1 этаж)</option>
                                            <option value="БВЗ (2 этаж)" {{ isset($data_CardObjectMain) && $data_CardObjectMain->location === "БВЗ (2 этаж)" ? 'selected' : '' }}>БВЗ (2 этаж)</option>
                                            <option value="ЦУП (1 этаж)" {{ isset($data_CardObjectMain) && $data_CardObjectMain->location === "ЦУП (1 этаж)" ? 'selected' : '' }}>ЦУП (1 этаж)</option>
                                            <option value="ЦУП (2 этаж)" {{ isset($data_CardObjectMain) && $data_CardObjectMain->location === "ЦУП (2 этаж)" ? 'selected' : '' }}>ЦУП (2 этаж)</option>
                                            <option value="Офис (1 этаж)" {{ isset($data_CardObjectMain) && $data_CardObjectMain->location === "Офис (1 этаж)" ? 'selected' : '' }}>Офис (1 этаж)</option>
                                            <option value="Офис (2 этаж)" {{ isset($data_CardObjectMain) && $data_CardObjectMain->location === "Офис (2 этаж)" ? 'selected' : '' }}>Офис (2 этаж)</option>
                                            <option value="Офис (3 этаж)" {{ isset($data_CardObjectMain) && $data_CardObjectMain->location === "Офис (3 этаж)" ? 'selected' : '' }}>Офис (3 этаж)</option>
                                            <option value="Серверная" {{ isset($data_CardObjectMain) && $data_CardObjectMain->location === "Серверная" ? 'selected' : '' }}>Серверная</option>
                                            <option value="Основной склад" {{ isset($data_CardObjectMain) && $data_CardObjectMain->location === "Основной склад" ? 'selected' : '' }}>Основной склад</option>
                                            <option value="Мезонин" {{ isset($data_CardObjectMain) && $data_CardObjectMain->location === "Мезонин" ? 'selected' : '' }}>Мезонин</option>
                                        </select>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <label class="w-100" for="curator">Куратор</label>
                                        <select id="curator" name="curator" class="form-select w-100">
                                            <option value="" disabled selected>Выберите куратора</option>
                                            @foreach($curators as $curator)
                                                <option value="{{ $curator->name }}" {{ isset($data_CardObjectMain) && $data_CardObjectMain->curator === $curator->name ? 'selected' : '' }}>{{ $curator->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex flex-column gap-3 w-50">
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <label class="w-100">Дата прихода</label>
                                        <input class="form-control w-100" type="date" name="date_arrival"
                                               value="{{ isset($data_CardObjectMain->date_arrival) ? $data_CardObjectMain->date_arrival : 'нет данных' }}">
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <label class="w-100">Дата ввода в эксплуатацию</label>
                                        <input class="form-control w-100"  type="date" name="date_usage"
                                               value="{{ isset($data_CardObjectMain->date_usage) ? $data_CardObjectMain->date_usage : 'нет данных' }}">
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <label class="w-100">Дата окончания аттестации/гарантии</label>
                                        <input class="form-control w-100" type="date"  name="date_cert_end"
                                               value="{{ isset($data_CardObjectMain->date_cert_end) ? $data_CardObjectMain->date_cert_end : 'нет данных' }}">
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <label class="w-100">Дата вывода из эксплуатации</label>
                                        <input class="form-control  w-100" type="date" name="date_usage_end"
                                               value="{{ isset($data_CardObjectMain->date_usage_end) ?$data_CardObjectMain->date_usage_end : 'нет данных' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ДОКУМЕНТАЦИЯ --}}
                    <div class="member_card_style documentation">
                        <div class="member-info">
                            <div class="d-flex justify-content-between mb-4">
                                <h4>Документация</h4>
                                <label for="docUpload" class="btn btn-primary">Вложить документ</label>
                                <input type="file" id="docUpload" class="d-none" multiple
                                       accept=".pdf, .doc, .docx">
                            </div>
                            <div class="objectDocs" id="documentList">
                                    @if ($data_CardObjectMainDocs !== null)
                                        @foreach ($data_CardObjectMainDocs as $file)
                                            <div class="documentItem">
                                                <a href="{{ route('downloadDocument', $file->id) }}">{{ $file->file_name }}</a>
                                                <i class="bi bi-x-circle docDelete ms-3"></i>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>Нет доступных документов</p>
                                    @endif
                            </div>
                        </div>
                    </div>
                    {{-- ИЗОБРАЖЕНИЕ --}}
                    <div class="member_card_style image">
                        <div class="member-info">
                            <div class="d-flex justify-content-between mb-4">
                                <h4>Изображение объекта</h4>
                                <label for="imageUpload" class="btn btn-primary">Загрузить</label>
                                <input type="file" id="imageUpload" class="d-none" multiple accept="image/*">
                            </div>
                            <div class="objectImage">
                                @if ($data_CardObjectMain)
                                    <img src="{{ route('getImage', ['id' => $data_CardObjectMain->id]) }}"
                                         alt="Image">
                                    <div class="objectImage__delete mt-4"><button class="btn btn-danger imageDelete">Удалить</button></div>
                                @else
                                    <p>Нет доступных изображений</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ВКЛАДКА "ОБСЛУЖИВАНИЕ" --}}
            @foreach ($data_CardObjectMain->services as $key => $service)
                <div class="tab-pane fade @if ($key === 0) @endif" id="service_{{ $key + 1 }}" role="tabpanel"
                     aria-labelledby="service_{{ $key + 1 }}-tab">
                    <button class="btn btn-danger mt-3 delete_service">Удалить</button>
                    <div id="service__blocks" class="d-grid">
                        {{-- ОБСЛУЖИВАНИЕ ТРМ --}}
                        <input type="hidden" id="service_id_{{ $key + 1 }}" name="service_id_{{ $key + 1 }}" value="{{ $service->id }}">
                        <div class="member_card_style services">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Обслуживание ТРМ {{ $key + 1 }}</h4>
{{--                                    <button class="btn btn-primary">Обновить даты</button>--}}
                                    <div>
                                        <input type="checkbox" class="form-check-input me-1" id="disableInTable_{{ $key + 1 }}"
                                               @if ($service->checked) checked @endif>
                                        <label class="form-check-label disableInTable" for="disableInTable_{{ $key + 1 }}">Не
                                            выводить
                                            на основной
                                            экран, в график TPM и не отправлять уведомления</label>
                                    </div>
                                </div>
                                <div class="member-info--inputs d-flex gap-5">
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100" for="service_type_{{ $key + 1 }}">Вид обслуживания</label>
                                            <select id="service_type_{{ $key + 1 }}" class="form-select" name="service_type">
                                                <option value="" disabled selected>Выберите вид</option>
                                                <option value="Регламентные работы" {{ $service->service_type == 'Регламентные работы' ? 'selected' : '' }}>Регламентные работы</option>
                                                <option value="Техническое обслуживание" {{ $service->service_type == 'Техническое обслуживание' ? 'selected' : '' }}>Техническое обслуживание</option>
                                                <option value="Сервисное техническое обслуживание" {{ $service->service_type == 'Сервисное техническое обслуживание' ? 'selected' : '' }}>Сервисное техническое обслуживание</option>
                                                <option value="Капитальный ремонт" {{ $service->service_type == 'Капитальный ремонт' ? 'selected' : '' }}>Капитальный ремонт</option>
                                                <option value="Аварийный ремонт" {{ $service->service_type == 'Аварийный ремонт' ? 'selected' : '' }}>Аварийный ремонт</option>
                                            </select>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Сокращенное название</label>
                                            <input id="short_name_{{ $key + 1 }}" name="short_name" class="form-control w-100" value="{{ $service->short_name }}" >
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100" for="performer_{{ $key + 1 }}">Исполнитель</label>
                                            <select id="performer_{{ $key + 1 }}" name="performer" class="form-select w-100">
                                                <option value="" disabled selected>Выберите исполнителя</option>
                                                @foreach($executors as $executor)
                                                    <option value="{{ $executor->name }}" {{ $service->performer === $executor->name ? 'selected' : '' }}>{{ $executor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100" for="responsible_{{ $key + 1 }}">Ответственный</label>
                                            <select id="responsible_{{ $key + 1 }}" name="responsible" class="form-select w-100">
                                                <option value="" disabled selected>Выберите ответственного</option>
                                                @foreach($responsibles as $responsible)
                                                    <option value="{{ $responsible->name }}" {{ $service->responsible === $responsible->name ? 'selected' : '' }}>{{ $responsible->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100" for="frequency_{{ $key + 1 }}">Периодичность</label>
                                            <select id="frequency_{{ $key + 1 }}" class="form-select" name="frequency">
                                                <option value="" disabled selected>Выберите периодичность</option>
                                                <option value="Сменное" {{ $service->frequency == 'Сменное' ? 'selected' : '' }}>Сменное</option>
                                                <option value="Ежемесячное" {{ $service->frequency == 'Ежемесячное' ? 'selected' : '' }}>Ежемесячное</option>
                                                <option value="Ежеквартальное" {{ $service->frequency == 'Ежеквартальное' ? 'selected' : '' }}>Ежеквартальное</option>
                                                <option value="Полугодовое" {{ $service->frequency == 'Полугодовое' ? 'selected' : '' }}>Полугодовое</option>
                                                <option value="Ежегодное" {{ $service->frequency == 'Ежегодное' ? 'selected' : '' }}>Ежегодное</option>
                                            </select>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата предыдущего обслуживания</label>
                                            <input id="prev_maintenance_date_{{ $key + 1 }}" type="date" class="form-control w-100" name="prev_maintenance_date" value="{{ $service->prev_maintenance_date }}" >
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Плановая дата обслуживания</label>
                                            <input id="planned_maintenance_date_{{ $key + 1 }}" type="date" class="form-control w-100" name="planned_maintenance_date" value="{{ $service->planned_maintenance_date }}"
                                                   readonly style="opacity: 0.5;">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Цвет в календаре</label>
                                            <div class="color-options">
                                                <div class="color-option red" data-color="#ff0000"></div>
                                                <div class="color-option green" data-color="#00ff00"></div>
                                                <div class="color-option blue" data-color="#0000ff"></div>
                                                <div class="color-option yellow" data-color="#fff400"></div>
                                            </div>
                                            <input type="hidden" id="selectedColor_{{ $key + 1 }}" name="selectedColor" value="{{ $service->calendar_color }}" >
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
                                    <div class="tooltip-wrapper">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#typesModal">Добавить вид работ
                                        </button>
                                    </div>
                                </div>
                                <div class="typesOfWork" id="typesOfWork">
                                    <!-- Используем класс row для создания строки -->
                                    <div class="grid-container">
                                        @foreach ($service->services_types as $type)
                                            <div class="grid-item">
                                                <div class="form-check d-flex align-items-center gap-2">
                                                    <input type="hidden" name="services[{{ $key }}][id]" value="{{ $type->id }}">
                                                    <input class="form-control" name="types_of_work[service_{{ $key + 1 }}][]"
                                                           value="{{ $type->type_work }}">
                                                    <i class="bi bi-x-circle typesOfWork_Delete ms-3"></i>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
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
                                    <textarea class="form-control materialsTextArea"  id="materialsTextArea_{{ $key + 1 }}">{{ $service->consumable_materials }}</textarea>
                                </div>
                            </div>
                        </div>
                        {{-- ИЗОБРАЖЕНИЕ --}}
                        <div class="member_card_style image">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Изображение объекта</h4>
                                    <label for="imageUpload" class="btn btn-primary">Загрузить</label>
                                    <input type="file" id="imageUpload" class="d-none" multiple accept="image/*">
                                </div>
                                <div class="objectImage">
                                    @if ($data_CardObjectMain)
                                        <img src="{{ route('getImage', ['id' => $data_CardObjectMain->id]) }}"
                                             alt="Image">
                                        <div class="objectImage__delete mt-4"><button class="btn btn-danger imageDelete">Удалить</button></div>
                                    @else
                                        <p>Нет доступных изображений</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @if ($data_CardObjectMain->services->isNotEmpty())
                <script>
                    //------------  обработчик удаления данных об обслуживании ------------
                    $(".delete_service").click(function () {
                        let serviceIndex = $(this).closest('.tab-pane').index() + 1; // Индекс вкладки обслуживания
                        let serviceId = "{{ $data_CardObjectMain->services[$key]->id }}"; // Идентификатор обслуживания
                        // Отправляем запрос на удаление обслуживания на сервер
                        $.ajax({
                            type: "DELETE",
                            url: "/delete-service/{{ $data_CardObjectMain->id }}/" + serviceId,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                // Обновляем страницу или выполняем другие действия после успешного удаления обслуживания
                                alert("Обслуживание успешно удалено");
                                // Перезагрузка страницы
                                location.reload();
                            },
                            error: function (error) {
                                // Обработка ошибки удаления обслуживания
                                alert("Ошибка при удалении обслуживания");
                                console.error(error);
                            }
                        });
                    });
                </script>
            @endif
        </div>
    </div>

    <!-- Добавить виды работ модальное окно -->
    <div class="modal fade" id="typesModal" tabindex="-1" aria-labelledby="typesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="typesModalLabel"><strong>Добавление вида работ</strong></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-1">
                        <label class="w-50">Вид работы</label>
                        <input name="" placeholder="Введите название вида работы" class="form-control w-100"
                               id="typeOfWorkInput">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" id="addTypeOfWork">Добавить</button>
                </div>
            </div>
        </div>
    </div>

    @php
        $imageSrc = $data_CardObjectMain ? route('getImage', ['id' => $data_CardObjectMain->id]) : 'https://placehold.it/350x450';
    @endphp
    <script>
        let uploadedImageSrc = '{{ $imageSrc }}'; // Переменная для хранения пути к загруженному изображению

        document.addEventListener('DOMContentLoaded', function () {
            let formData = new FormData();
            // Обработчик загрузки документов
            $('#docUpload').change(function () {
                let fileList = this.files;
                let documentList = $('#documentList');
                // documentList.empty(); // Очищаем список документов перед добавлением новых

                for (let i = 0; i < fileList.length; i++) {
                    let file = fileList[i];
                    let fileName = file.name;
                    let listItem = $('<a>').attr('href', '#').text(fileName);
                    let deleteButton = $('<i class="bi bi-x-circle docDelete ms-3"></i>');
                    let documentItem = $('<div class="documentItem">').append(listItem, deleteButton);
                    documentList.append(documentItem);
                    // Добавляем файл к formData
                    formData.append('files[]', file);
                }
            });
            $(document).on('click', '.docDelete', function () {
                let parent = $(this).closest('.documentItem');
                let fileName = parent.find('a').text();
                parent.remove();
                formData.append('files_delete[]', fileName); // Добавляем имя файла к formData
                // При удалении файла из списка, удаляем его из formData
                formData.delete('files[]', fileName);
            });

            // Обработчик загрузки изображений
            $('#imageUpload').change(function () {
                let fileList = this.files;
                if (fileList.length > 0) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        uploadedImageSrc = e.target.result; // Сохраняем путь к загруженному изображению
                        $('.objectImage img').attr('src', uploadedImageSrc); // Отображаем изображение на вкладке "Основная"
                        $('.member_card_style.image .objectImage img').attr('src', uploadedImageSrc); // Отображаем изображение на других вкладках
                    }
                    reader.readAsDataURL(fileList[0]);

                    // Добавляем изображение к formData
                    formData.append('images[]', fileList[0]);
                }
            });
            $(document).on('click', '.imageDelete', function () {
                // Находим родительский элемент кнопки "Удалить"
                let parent = $(this).closest('.member_card_style.image .member-info');
                // Удаляем изображение из родительского элемента
                parent.find('.objectImage img').attr('src', 'https://placehold.it/350x450'); // Устанавливаем атрибут src пустой строкой
                // Удаляем кнопку "Удалить"
                $(this).closest('.objectImage__delete').remove();
            });


            // ------------ динамическое создание вкладок обслуживание  ------------

            // Находим максимальный номер вкладки обслуживания
            let maxServiceTabsCount = 0;
            $('.nav-link').each(function() {
                let tabId = $(this).attr('id');
                if (tabId && tabId.startsWith('service_')) {
                    let tabIndex = parseInt(tabId.split('_')[1]);
                    maxServiceTabsCount = Math.max(maxServiceTabsCount, tabIndex);
                }
            });
            // Определяем следующий номер для вкладки обслуживания
            let serviceTabsCount = maxServiceTabsCount + 1;
            const maxServiceTabs = 5; // максимальное количество вкладок
            // Обработчик нажатия на кнопку "Создать обслуживание"
            $('.createService').on('click', function () {
                if (serviceTabsCount >= maxServiceTabs) {
                    alert('Нельзя добавить больше 4 видов обслуживания');
                    $(this).prop('disabled', true).css('opacity', 0.5); // Делаем кнопку неактивной и изменяем её стиль
                    return; // Прекращаем выполнение функции, если достигнут лимит вкладок
                }
                // Генерируем id для новой вкладки и ее содержимого
                let tabId = 'service_' + serviceTabsCount + '-tab';
                let paneId = 'service_' + serviceTabsCount;

                // Создаем новую вкладку и ее содержимое
                let tab = $('<li class="nav-item" role="presentation"> \
                        <button class="nav-link" id="' + tabId + '" data-bs-toggle="tab" data-bs-target="#' + paneId + '" type="button" role="tab" aria-controls="' + paneId + '" aria-selected="false">ОБСЛУЖИВАНИЕ ' + serviceTabsCount + '</button> \
                    </li>');
                let tabContent = $('<div class="tab-pane fade" id="' + paneId + '" role="tabpanel" aria-labelledby="' + tabId + '"> \
                        <div id="service__blocks" class="d-grid"> \
                            {{-- ОБСЛУЖИВАНИЕ ТРМ --}} \
                            <div class="member_card_style services"> \
                                <div class="member-info"> \
                                    <div class="d-flex justify-content-between mb-4"> \
                                        <h4>Обслуживание ТРМ</h4> \
                                        <!-- <button class="btn btn-primary">Обновить даты</button> --> \
                                        <div> \
                                            <input type="checkbox" class="form-check-input me-1" id="disableInTable_' + serviceTabsCount + '"> \
                                            <label class="form-check-label disableInTable" for="disableInTable_' + serviceTabsCount + '">Не выводить \
                                                на основной \
                                                экран, в график TPM и не отправлять уведомления</label> \
                                        </div> \
                                    </div> \
                                    <div class="member-info--inputs d-flex gap-5"> \
                                        <div class="d-flex flex-column gap-3 w-50"> \
                                            <div class="d-flex justify-content-between align-items-center gap-3"> \
                                                <label class="w-100" for="service_type_' + serviceTabsCount + '">Вид обслуживания</label> \
                                                <select id="service_type_' + serviceTabsCount + '" class="form-select" name="service_type">\
                                                        <option value="" disabled selected>Выберите вид</option>\
                                                        <option value="Регламентные работы">Регламентные работы</option>\
                                                        <option value="Техническое обслуживание">Техническое обслуживание</option>\
                                                        <option value="Сервисное техническое обслуживание">Сервисное техническое обслуживание</option>\
                                                        <option value="Капитальный ремонт">Капитальный ремонт</option>\
                                                        <option value="Аварийный ремонт">Аварийный ремонт</option>\
                                                </select>\
                                            </div> \
                                            <div class="d-flex justify-content-between align-items-center gap-3"> \
                                                <label class="w-100" for="short_name_' + serviceTabsCount + '">Сокращенное название</label> \
                                                <input id="short_name_' + serviceTabsCount + '" name="short_name" placeholder="Введите сокращенное название" \
                                                    class="form-control w-100"> \
                                            </div> \
                                     <div class="d-flex justify-content-between align-items-center gap-3"> \
                                            <label class="w-100" for="performer_' + serviceTabsCount + '">Исполнитель</label> \
                                            <select id="performer_' + serviceTabsCount + '" name="performer" class="form-select w-100">\
                                             <option value="" disabled selected>Выберите исполнителя</option>\
                                              @foreach($executors as $executor)\
                                               <option value="{{ $executor->name }}">{{ $executor->name }}</option>\
                                              @endforeach\
                                            </select>\
                                            </div>\
                                            <div class="d-flex justify-content-between align-items-center gap-3"> \
                                            <label class="w-100" for="responsible_' + serviceTabsCount + '">Ответственный</label> \
                                            <select id="responsible_' + serviceTabsCount + '" name="responsible" class="form-select w-100">\
                                              <option value="" disabled selected>Выберите ответственного</option>\
                                                 @foreach($responsibles as $responsible)\
                                                  <option value="{{ $responsible->name }}">{{ $responsible->name }}</option>\
                                                  @endforeach\
                                               </select>\
                                            </div>\
                                        </div> \
                                        <div class="d-flex flex-column gap-3 w-50"> \
                                            <div class="d-flex justify-content-between align-items-center gap-3"> \
                                                <label class="w-100" for="frequency_' + serviceTabsCount + '">Периодичность</label> \
                                                 <select id="frequency_' + serviceTabsCount + '"class="form-select" name="frequency">\
                                                        <option value="" disabled selected>Выберите периодичность</option>\
                                                        <option value="Сменное">Сменное</option>\
                                                        <option value="Ежемесячное">Ежемесячное</option>\
                                                        <option value="Ежеквартальное">Ежеквартальное</option>\
                                                        <option value="Полугодовое">Полугодовое</option>\
                                                        <option value="Ежегодное">Ежегодное</option>\
                                                </select>\
                                            </div> \
                                            <div class="d-flex justify-content-between align-items-center gap-3"> \
                                                <label class="w-100" for="prev_maintenance_date_' + serviceTabsCount + '">Дата предыдущего обслуживания</label> \
                                                <input type="date" id="prev_maintenance_date_' + serviceTabsCount + '" name="prev_maintenance_date" class="form-control w-100" \
                                                    placeholder="Введите дату предыдущего обслуживания"> \
                                            </div> \
                                            <div class="d-flex justify-content-between align-items-center gap-3"> \
                                                <label class="w-100" for="planned_maintenance_date_' + serviceTabsCount + '">Плановая дата обслуживания</label> \
                                                <input type="date" id="planned_maintenance_date_' + serviceTabsCount + '" name="planned_maintenance_date" class="form-control w-100" \
                                                    placeholder="Введите плановую дату обслуживания"> \
                                            </div> \
                                            <div class="d-flex justify-content-between align-items-center gap-3"> \
                                                <label class="w-100">Цвет в календаре</label> \
                                                <div class="color-options" data-toggle="tooltip" title="нажмите на выбранный цвет"> \
                                                    <div class="color-option red" data-color="#ff0000"></div> \
                                                    <div class="color-option green" data-color="#00ff00"></div> \
                                                    <div class="color-option blue" data-color="#0000ff"></div> \
                                                    <div class="color-option yellow" data-color="#fff400"></div> \
                                                </div> \
                                                <input type="hidden" id="selectedColor_' + serviceTabsCount + '" name="selectedColor"> \
                                            </div> \
                                        </div> \
                                    </div> \
                                </div> \
                            </div> \
                            \
                            \
                             {{-- ВИДЫ РАБОТ --}}\
                             <div class="member_card_style types" data-service-id="' + paneId + '">\
                                    <div class="member-info">\
                                    <div class="d-flex justify-content-between mb-4">\
                                    <h4>Виды работ</h4>\
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#typesModal">Добавить вид работ</button>\
                            </div>\
                                        <div class="typesOfWork" id="typesOfWork">\
                                    <!-- Используем класс row для создания строки -->\
                                    <div class="grid-container">\
                                        <!-- Используем класс col-md-6 для создания двух столбцов на широких экранах -->\
                                        <div class="grid-item">\
                                            <div class="form-check d-flex align-items-center gap-2">\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                            </div>\
                            \
                            \
                            {{-- РАСХОДНЫЕ МАТЕРИАЛЫ --}}\
                            <div class="member_card_style materials">\
                                <div class="member-info">\
                                <div class="d-flex justify-content-between mb-4">\
                                <h4>Расходные материалы и ЗИП</h4>\
                                 </div>\
                                <div class="material_text w-100">\
                                <textarea id="materialsTextArea_' + serviceTabsCount + '" \
                                class="form-control materialsTextArea" \
                                placeholder="Введите расходные материалы и ЗИП"></textarea>\
                            </div>\
                            </div>\
                             </div>\
                            \
                            {{-- ИЗОБРАЖЕНИЕ --}}\
                            <div class="member_card_style image">\
                                <div class="member-info">\
                                 <div class="d-flex justify-content-between mb-4">\
                                    <h4>Изображение объекта</h4>\
                                <label for="imageUpload" class="btn btn-primary">Загрузить</label>\
                                <input type="file" id="imageUpload" class="d-none" multiple accept="image/*">\
                                </div>\
                            <div class="objectImage">\
                                  <img src="' + uploadedImageSrc + '"/>\
                            </div>\
                            </div>\
                            </div>\
                            </div>\
                            </div>\
                        </div> \
                    </div>');

                // Добавляем новую вкладку и ее содержимое к соответствующим элементам
                $('#cardObjectTab').append(tab);
                $('#cardObjectTabContent').append(tabContent);

                // Устанавливаем цвет в календаре для новой вкладки
                setColorOptions();

                // Обновляем обработчик событий для выбора цвета
                updateColorPicker();

                // Увеличиваем счетчик вкладок для обслуживания
                serviceTabsCount++;
            });
            // Проверка при загрузке страницы, чтобы кнопка была неактивной, если уже достигнут лимит
            if (serviceTabsCount >= maxServiceTabs) {
                $('.createService').prop('disabled', true).css('opacity', 0.5);
            }

            // Функция для установки выбранного цвета
            function setColorOptions() {
                $('.color-options').each(function() {
                    let selectedColor = $(this).siblings('input[name="selectedColor"]').val();
                    $(this).find('.color-option').each(function() {
                        if ($(this).data('color') === selectedColor) {
                            $(this).addClass('selected');
                        } else {
                            $(this).removeClass('selected');
                        }
                    });
                });
            }
            // Вызов функции установки выбранного цвета при загрузке страницы
            setColorOptions();

            // Функция для обновления обработчика событий для выбора цвета
            function updateColorPicker() {
                const colorOptions = $('.color-option');
                colorOptions.on('click', function() {
                    colorOptions.removeClass('selected');
                    $(this).addClass('selected');
                    const selectedColor = $(this).data('color');
                    const selectedColorField = $(this).closest('.d-flex').find('input[name="selectedColor"]');
                    selectedColorField.val(selectedColor);
                });
            }
            // Вызываем функцию для обновления обработчика событий для выбора цвета
            updateColorPicker();

            // Инициализируем объект typesOfWorkByService
            let typesOfWorkByService = {};
            // Обработка клика по кнопке "Добавить вид работы"
            $("#addTypeOfWork").click(function() {
                let typeOfWork = $("#typeOfWorkInput").val().trim();
                console.log("Добавлен вид работы:", typeOfWork);
                if (typeOfWork !== '') {
                    let currentServiceId = $('.tab-pane.active').attr('id');
                    let listItem = '<div class="grid-item">' +
                        '<div class="form-check d-flex align-items-center gap-2">' +
                        '<input class="form-control" ' +
                        'name="types_of_work[' + currentServiceId + '][]" value="' + typeOfWork + '">' +
                        '<i class="bi bi-x-circle typesOfWork_Delete ms-3"></i>' +
                        '</div>' +
                        '</div>';

                    // Добавляем вид работы в typesOfWorkByService
                    if (!typesOfWorkByService[currentServiceId]) {
                        typesOfWorkByService[currentServiceId] = [];
                    }
                    typesOfWorkByService[currentServiceId].push(typeOfWork);

                    $("#" + currentServiceId + " .grid-container").append(listItem);

                    // Выводим данные о типах работ в консоль для проверки
                    console.log("typesOfWorkByService:", typesOfWorkByService);
                }
            });
            $(document).on('click', '.typesOfWork_Delete', function () {
                // Находим родительский элемент блока типа работы
                let parent = $(this).closest('.grid-item');
                // Находим ввод с типом работы
                let typeOfWorkInput = parent.find('.form-control');
                // Получаем значение типа работы
                let typeOfWork = typeOfWorkInput.val().trim();
                // Скрываем родительский элемент блока типа работы
                parent.hide();
                // Добавляем имя типа работы к formData
                formData.append('types_of_work_delete[]', typeOfWork);
            });


            $(document).on('change', '[id^="prev_maintenance_date_"], [id^="frequency_"]', function () {
                updatePlannedMaintenanceDate();
            });

            function updatePlannedMaintenanceDate() {
                $('[id^="prev_maintenance_date_"]').each(function () {
                    let index = $(this).attr('id').split('_')[3];
                    let prevDateInput = $('#prev_maintenance_date_' + index);
                    let plannedDateInput = $('#planned_maintenance_date_' + index);
                    let frequency = $('#frequency_' + index).val();
                    let dateUsageInput = $('input[name="date_usage"]');

                    if (!prevDateInput.val() && !dateUsageInput.val()) return;

                    let initialDate = prevDateInput.val() ? new Date(prevDateInput.val()) : new Date(dateUsageInput.val());
                    let initialDay = initialDate.getDate();
                    let initialDayOfWeek = initialDate.getDay();

                    let nextMaintenanceDate = calculateNextMaintenanceDate(initialDate, frequency, initialDay, initialDayOfWeek);
                    plannedDateInput.val(nextMaintenanceDate.toISOString().slice(0, 10));
                });
            }

            function calculateNextMaintenanceDate(baseDate, frequency, initialDay, initialDayOfWeek) {
                let nextDate = new Date(baseDate);

                switch (frequency) {
                    case 'Ежемесячное':
                        nextDate.setMonth(nextDate.getMonth() + 1);
                        break;
                    case 'Ежеквартальное':
                        nextDate.setMonth(nextDate.getMonth() + 3);
                        break;
                    case 'Полугодовое':
                        nextDate.setMonth(nextDate.getMonth() + 6);
                        break;
                    case 'Ежегодное':
                        nextDate.setFullYear(nextDate.getFullYear() + 1);
                        break;
                    default:
                        return;
                }

                nextDate.setDate(initialDay);
                let closestDate = findClosestDayOfWeek(nextDate, initialDayOfWeek);
                return closestDate;
            }

            function findClosestDayOfWeek(baseDate, targetDayOfWeek) {
                let nextDate = new Date(baseDate);

                if (nextDate.getDay() === targetDayOfWeek) {
                    return nextDate;
                }

                let prevDate = new Date(baseDate);
                while (prevDate.getDay() !== targetDayOfWeek) {
                    prevDate.setDate(prevDate.getDate() - 1);
                }

                while (nextDate.getDay() !== targetDayOfWeek) {
                    nextDate.setDate(nextDate.getDate() + 1);
                }

                if (Math.abs(prevDate - baseDate) <= Math.abs(nextDate - baseDate)) {
                    return prevDate;
                } else {
                    return nextDate;
                }
            }

            // Обработчик изменения значения даты предыдущего обслуживания или периодичности
            // $(document).on('change', '[id^="prev_maintenance_date_"], [id^="frequency_"]', function () {
            //     // Обновляем плановую дату обслуживания при изменении периодичности или даты предыдущего обслуживания
            //     updatePlannedMaintenanceDate();
            // });
            // // Функция для обновления плановой даты обслуживания
            // function updatePlannedMaintenanceDate() {
            //     $('[id^="prev_maintenance_date_"]').each(function () {
            //         let index = $(this).attr('id').split('_')[3];
            //         let prevDateInput = $('#prev_maintenance_date_' + index);
            //         let plannedDateInput = $('#planned_maintenance_date_' + index);
            //         let frequency = $('#frequency_' + index).val();
            //         let dateUsageInput = $('input[name="date_usage"]');
            //
            //         // Если дата предыдущего обслуживания не указана и дата ввода в эксплуатацию отсутствует, выходим из функции
            //         if (!prevDateInput.val() && !dateUsageInput.val()) return;
            //
            //         // Если дата предыдущего обслуживания не указана, используем дату ввода в эксплуатацию
            //         let prevMaintenanceDate = prevDateInput.val() ? new Date(prevDateInput.val()) : new Date(dateUsageInput.val());
            //         let plannedMaintenanceDate = new Date(prevMaintenanceDate);
            //         let dayOfWeek = plannedMaintenanceDate.getDay();
            //
            //         let nextDate = new Date(prevMaintenanceDate);
            //         // Выполняем соответствующие расчеты в зависимости от выбранной периодичности
            //         switch (frequency) {
            //             case 'Ежемесячное':
            //                 nextDate.setMonth(nextDate.getMonth() + 1);
            //                 break;
            //             case 'Ежеквартальное':
            //                 nextDate.setMonth(nextDate.getMonth() + 3);
            //                 break;
            //             case 'Полугодовое':
            //                 nextDate.setMonth(nextDate.getMonth() + 6);
            //                 break;
            //             case 'Ежегодное':
            //                 nextDate.setFullYear(nextDate.getFullYear() + 1);
            //                 break;
            //             default:
            //                 // Если выбрана периодичность "Сменное" или что-то другое, выходим из функции
            //                 return;
            //         }
            //
            //         // Поиск ближайшей даты, соответствующей нужному дню недели
            //         let closestDate = findClosestDayOfWeek(nextDate, dayOfWeek);
            //
            //         // Устанавливаем новую плановую дату обслуживания
            //         plannedDateInput.val(closestDate.toISOString().slice(0, 10));
            //     });
            // }
            // // Функция для поиска ближайшего нужного дня недели
            // function findClosestDayOfWeek(baseDate, targetDayOfWeek) {
            //     let prevDate = new Date(baseDate);
            //     let nextDate = new Date(baseDate);
            //
            //     // Ищем ближайшие даты до и после базовой даты
            //     while (prevDate.getDay() !== targetDayOfWeek) {
            //         prevDate.setDate(prevDate.getDate() - 1);
            //     }
            //     while (nextDate.getDay() !== targetDayOfWeek) {
            //         nextDate.setDate(nextDate.getDate() + 1);
            //     }
            //
            //     // Возвращаем дату, которая ближе к базовой дате
            //     if (Math.abs(prevDate - baseDate) <= Math.abs(nextDate - baseDate)) {
            //         return prevDate;
            //     } else {
            //         return nextDate;
            //     }
            // }

            $(document).on('change', '[id^="service_type_"]', function () {
                console.log('Выбран вид обслуживания. Обновляем сокращенное название...');
                // Обновляем плановую дату обслуживания при изменении периодичности или даты предыдущего обслуживания
                updateInfrastructure();
            });

            // Функция для обновления сокращенного названия вида обслуживания
            function updateInfrastructure() {
                console.log('Обновляем сокращенное название вида обслуживания...');
                $('[id^="service_type_"]').each(function () {
                    let index = $(this).attr('id').split('_')[2];
                    let infrastructure = $('#service_type_' + index).val();
                    let shortNameInput = $('#short_name_' + index);

                    // Выполняем соответствующие действия в зависимости от выбранного вида обслуживания
                    switch (infrastructure) {
                        case 'Регламентные работы':
                            shortNameInput.val('РР');
                            break;
                        case 'Техническое обслуживание':
                            shortNameInput.val('ТО');
                            break;
                        case 'Сервисное техническое обслуживание':
                            shortNameInput.val('СТО');
                            break;
                        case 'Капитальный ремонт':
                            shortNameInput.val('КР');
                            break;
                        case 'Аварийный ремонт':
                            shortNameInput.val('АР');
                            break;
                        default:
                            // Если выбран неизвестный вид обслуживания, очищаем поле сокращенного названия
                            shortNameInput.val('');
                            break;
                    }
                });
            }

            //------------  обработчик сохранения данных  ------------
            $(".saveEditObject").click(function () {
                // Собираем данные с основной формы
                formData.append('infrastructure', $("select[name=infrastructure]").val());
                formData.append('name', $("input[name=name]").val());
                formData.append('curator', $("select[name=curator]").val());
                formData.append('number', $("input[name=number]").val());
                formData.append('location', $("select[name=location]").val());
                formData.append('date_arrival', $("input[name=date_arrival]").val());
                formData.append('date_usage', $("input[name=date_usage]").val());
                formData.append('date_cert_end', $("input[name=date_cert_end]").val());
                formData.append('date_usage_end', $("input[name=date_usage_end]").val());

                let servicesData = [];
                // Собираем данные с каждой вкладки обслуживания
                for (let i = 1; i < serviceTabsCount; i++) {
                    let serviceId = $("#service_id_" + i).val();
                    console.log(serviceId);
                    // let typesOfWorkValues = typesOfWorkByService['service_' + i] || [];
                    let currentServiceId = 'service_' + i;
                    let typesOfWorkValues = [];
                    $("#" + currentServiceId + " .typesOfWork input[name='types_of_work[" + currentServiceId + "][]']").each(function () {
                        let typeOfWorkId = $(this).siblings("input[name^='services']").val(); // Получаем id услуги
                        let typeOfWorkValue = $(this).val();
                        typesOfWorkValues.push({ id: typeOfWorkId, value: typeOfWorkValue }); // Добавляем id и значение в массив
                    });

                    let serviceData = {
                        id: serviceId,// Добавляем идентификатор услуги в данные
                        service_type: $("#service_type_" + i).val(),
                        short_name: $("#short_name_" + i).val(),
                        performer: $("#performer_" + i).val(),
                        responsible: $("#responsible_" + i).val(),
                        frequency: $("#frequency_" + i).val(),
                        prev_maintenance_date: $("#prev_maintenance_date_" + i).val(),
                        planned_maintenance_date: $("#planned_maintenance_date_" + i).val(),
                        selectedColor: $("#selectedColor_" + i).val(),
                        materials: $("#materialsTextArea_" + i).val(),
                        types_of_work: typesOfWorkValues,
                        checked: $('#disableInTable_' + i).is(':checked') // Добавляем значение чекбокса "не выводить"
                    };

                    serviceData.types_of_work = typesOfWorkValues;

                    // Добавляем данные в массив servicesData
                    servicesData.push(serviceData);
                    console.log(serviceData);
                }

                // Добавляем массив servicesData в formData
                formData.append("services", JSON.stringify(servicesData));

                // Отправляем данные на сервер
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/edit-card-object-save/{{ $data_CardObjectMain->id }}",
                    data: formData,
                    processData: false, // Не обрабатывать данные
                    contentType: false, // Не устанавливать тип содержимого
                    success: function (response) {
                        // Обработка успешного ответа от сервера (например, отображение сообщения об успешном сохранении)
                        // alert("Данные для карточки объекта успешно обновлены!");
                     window.location.href = "{{ route('cardObject', ['id' => $data_CardObjectMain->id]) }}";
                        //console.log(formData);
                    },
                    error: function (error) {
                        // Обработка ошибки при сохранении данных
                        alert("Ошибка при обновлении данных для карточки объекта!");
                        console.log(formData);
                    }
                });
            });

        });

    </script>
@endsection
