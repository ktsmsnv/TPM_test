{{--страница СОЗДАНИЕ карточки объекта --}}
@extends('layouts.app')

@section('content')
    <div class="container custom_tab_style1_outer">
        <div class="row">
            {{-- ЗАГОЛОВОК С ПАНЕЛЬЮ КНОПОК --}}
            <div class="col-md-12 text-left">
                <h1 class="mb-4"><strong>Создание карточки объекта</strong></h1>
            </div>
            <div class="btns d-flex mb-5">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success saveCard">Сохранить</button>
                    <a href="/home" type="button" class="btn btn-secondary me-5">Закрыть</a>

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
                                            {{-- <input name="infrastructure" placeholder="Введите вид инфраструктуры" class="form-control w-100">--}}
                                            <select class="form-select" name="infrastructure">
                                                <option value="" disabled selected>Выберите вид</option>
                                                <option value="Технологическая">Технологическая</option>
                                                <option value="Информационная">Информационная</option>
                                                <option value="Бытовая">Бытовая</option>
                                                <option value="Инженерная">Инженерная</option>
                                                <option value="Электротехническая">Электротехническая</option>
                                                <option value="Безопасность">Безопасность</option>
                                            </select>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Наименование объекта</label>
                                            <input name="name" placeholder="Введите наименование объекта"
                                                   class="form-control w-100">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Инв./заводской №</label>
                                            <input class="form-control w-100" name="number"
                                                   placeholder="Введите инв./заводской №">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Место установки</label>
                                            {{-- <input class="form-control  w-100" name="location" placeholder="Введите место установки">--}}
                                            <select class="form-select" name="location">
                                                <option value="" disabled selected>Выберите место</option>
                                                <option value="Участок ЭОБ">Участок ЭОБ</option>
                                                <option value="Участок сборки">Участок сборки</option>
                                                <option value="БВЗ (1 этаж)">БВЗ (1 этаж)</option>
                                                <option value="БВЗ (2 этаж)">БВЗ (2 этаж)</option>
                                                <option value="ЦУП (1 этаж)">ЦУП (1 этаж)</option>
                                                <option value="ЦУП (2 этаж)">ЦУП (2 этаж)</option>
                                                <option value="Офис (1 этаж)">Офис (1 этаж)</option>
                                                <option value="Офис (2 этаж)">Офис (2 этаж)</option>
                                                <option value="Офис (3 этаж)">Офис (3 этаж)</option>
                                                <option value="Серверная">Серверная</option>
                                                <option value="Основной склад">Основной склад</option>
                                                <option value="Мезонин">Мезонин</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата прихода</label>
                                            <input type="date" class="form-control w-100" name="date_arrival"
                                                   placeholder="Введите дату прихода">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата ввода в эксплуатацию</label>
                                            <input type="date" class="form-control w-100" name="date_usage"
                                                   placeholder="Введите дату ввода в эксплуатацию">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата окончания аттестации/гарантии</label>
                                            <input type="date" class="form-control w-100" name="date_cert_end"
                                                   placeholder="Введите дату окончания аттестации/гарантии">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата вывода из эксплуатации</label>
                                            <input type="date" class="form-control  w-100" name="date_usage_end"
                                                   placeholder="Введите дату вывода из эксплуатации">
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
                                    <!-- Здесь будут отображаться загруженные документы -->
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
                                    <img src="http://placehold.it/350x450" id="uploadedImage" alt="Uploaded Image">
                                </div>
                            </div>
                        </div>

                    </div>
                    {{-- ВКЛАДКА "ОБСЛУЖИВАНИЕ" --}}
                </div>

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


        <script>
            let uploadedImageSrc = null; // Переменная для хранения пути к загруженному изображению

            document.addEventListener('DOMContentLoaded', function () {
                // Обработчик загрузки документов
                $('#docUpload').change(function () {
                    let fileList = this.files;
                    let documentList = $('#documentList');
                    documentList.empty(); // Очищаем список документов перед добавлением новых

                    for (let i = 0; i < fileList.length; i++) {
                        let file = fileList[i];
                        let fileName = file.name;
                        let listItem = $('<a>').attr('href', '#').text(fileName);
                        let deleteButton = $('<i class="bi bi-x-circle docDelete ms-3"></i>');
                        let documentItem = $('<div class="documentItem">').append(listItem, deleteButton);
                        documentList.append(documentItem);
                    }
                });
                $(document).on('click', '.docDelete', function () {
                    // Находим родительский элемент строки документации, содержащий нажатую кнопку "Удалить документ"
                    let parent = $(this).closest('.documentItem');
                    // Удаляем эту строку документации
                    parent.remove();
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
                            $('.member_card_style.image .member-info').append(
                                '<div class="objectImage__delete mt-4"><button class="btn btn-danger imageDelete">Удалить</button></div>'
                            );
                        }
                        reader.readAsDataURL(fileList[0]);
                    }
                });
                $(document).on('click', '.imageDelete', function () {
                    // Находим родительский элемент кнопки "Удалить"
                    let parent = $(this).closest('.member_card_style.image .member-info');
                    // Удаляем изображение из родительского элемента
                    parent.find('.objectImage img').attr('src', 'http://placehold.it/350x450'); // Устанавливаем атрибут src пустой строкой
                    // Удаляем кнопку "Удалить"
                    $(this).closest('.objectImage__delete').remove();
                });


                // ------------ динамическое создание вкладок обслуживание  ------------
                let serviceTabsCount = 1; // начальный счетчик вкладок для обслуживания
                // Обработчик нажатия на кнопку "Создать обслуживание"
                $('.createService').on('click', function () {
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
                                        <button class="btn btn-primary">Обновить даты</button> \
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
                                                <input id="performer_' + serviceTabsCount + '" name="performer" class="form-control w-100" \
                                                    placeholder="Введите исполнителя"> \
                                            </div> \
                                            <div class="d-flex justify-content-between align-items-center gap-3"> \
                                                <label class="w-100" for="responsible_' + serviceTabsCount + '">Ответственный</label> \
                                                <input id="responsible_' + serviceTabsCount + '" name="responsible" class="form-control  w-100" \
                                                    placeholder="Введите ответственного"> \
                                            </div> \
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
                                <!-- Добавляем textarea с атрибутом placeholder -->\
                                <textarea id="materialsTextArea_' + serviceTabsCount + '" class="form-control materialsTextArea" placeholder="Введите расходные материалы и ЗИП"></textarea>\
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

                    // Обновляем обработчик событий для выбора цвета
                    updateColorPicker();

                        // Увеличиваем счетчик вкладок для обслуживания
                        serviceTabsCount++;
                });
                // Функция для обновления обработчика событий для выбора цвета
                function updateColorPicker() {
                    // Получаем все блоки цветов
                    const colorOptions = $('.color-option');
                    // Добавляем обработчик события для каждого блока цвета
                    colorOptions.on('click', function () {
                        // Убираем рамку у всех блоков цветов
                        colorOptions.removeClass('selected');
                        // Добавляем рамку только выбранному блоку цвета
                        $(this).addClass('selected');
                        // Получаем цвет выбранного блока
                        const selectedColor = $(this).data('color');
                        // Находим скрытое поле выбранного цвета для текущей вкладки
                        const selectedColorField = $(this).closest('.tab-pane').find('input[name="selectedColor"]');
                        // Устанавливаем значение цвета в скрытое поле ввода текущей вкладки
                        selectedColorField.val(selectedColor);
                    });
                }
                // Вызываем функцию для обновления обработчика событий для выбора цвета
                updateColorPicker();

                // Инициализируем объект typesOfWorkByService
                let typesOfWorkByService = {};
                let formData = new FormData();
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

                        $("#" + currentServiceId + " .typesOfWork").append(listItem);
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
                });

                // Обработчик изменения значения даты предыдущего обслуживания или периодичности
                $(document).on('change', '[id^="prev_maintenance_date_"], [id^="frequency_"]', function () {
                    // Обновляем плановую дату обслуживания при изменении периодичности или даты предыдущего обслуживания
                    updatePlannedMaintenanceDate();
                });
                // Функция для обновления плановой даты обслуживания
                function updatePlannedMaintenanceDate() {
                    $('[id^="prev_maintenance_date_"]').each(function () {
                        let index = $(this).attr('id').split('_')[3];
                        let prevDateInput = $('#prev_maintenance_date_' + index);
                        let plannedDateInput = $('#planned_maintenance_date_' + index);
                        let frequency = $('#frequency_' + index).val();
                        let dateUsageInput = $('input[name="date_usage"]');

                        // Если дата предыдущего обслуживания не указана и дата ввода в эксплуатацию отсутствует, выходим из функции
                        if (!prevDateInput.val() && !dateUsageInput.val()) return;

                        // Если дата предыдущего обслуживания не указана, используем дату ввода в эксплуатацию
                        let prevMaintenanceDate = prevDateInput.val() ? new Date(prevDateInput.val()) : new Date(dateUsageInput.val());
                        let plannedMaintenanceDate = new Date(prevMaintenanceDate);

                        // Выполняем соответствующие расчеты в зависимости от выбранной периодичности
                        switch (frequency) {
                            case 'Ежемесячное':
                                plannedMaintenanceDate.setMonth(plannedMaintenanceDate.getMonth() + 1);
                                break;
                            case 'Ежеквартальное':
                                plannedMaintenanceDate.setMonth(plannedMaintenanceDate.getMonth() + 3);
                                break;
                            case 'Полугодовое':
                                plannedMaintenanceDate.setMonth(plannedMaintenanceDate.getMonth() + 6);
                                break;
                            case 'Ежегодное':
                                plannedMaintenanceDate.setFullYear(plannedMaintenanceDate.getFullYear() + 1);
                                break;
                            default:
                                // Если выбрана периодичность "Сменное" или что-то другое, выходим из функции
                                return;
                        }

                        // Устанавливаем новую плановую дату обслуживания
                        plannedDateInput.val(plannedMaintenanceDate.toISOString().slice(0, 10));
                    });
                }


                // Обработчик изменения значения вида обслуживания
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
                $(".saveCard").click(function () {
                    // Собираем данные с основной формы
                    formData.append('infrastructure', $("select[name=infrastructure]").val());
                    formData.append('name', $("input[name=name]").val());
                    formData.append('number', $("input[name=number]").val());
                    formData.append('location', $("select[name=location]").val());
                    formData.append('date_arrival', $("input[name=date_arrival]").val());
                    formData.append('date_usage', $("input[name=date_usage]").val());
                    formData.append('date_cert_end', $("input[name=date_cert_end]").val());
                    formData.append('date_usage_end', $("input[name=date_usage_end]").val());

                    // Собираем данные о загруженных изображениях
                    let imageFiles = $("#imageUpload")[0].files;
                    for (let i = 0; i < imageFiles.length; i++) {
                        formData.append('images[]', imageFiles[i]);
                    }

                    // Собираем данные о загруженных файлах
                    let docFiles = $("#docUpload")[0].files;
                    for (let j = 0; j < docFiles.length; j++) {
                        formData.append('files[]', docFiles[j]);
                    }

                    let servicesData = [];
                    // Собираем данные с каждой вкладки обслуживания
                    for (let i = 1; i < serviceTabsCount; i++) {
                        let typesOfWorkValues = typesOfWorkByService['service_' + i] || [];
                        let serviceData = {
                            service_type: $("#service_type_" + i).val(),
                            short_name: $("#short_name_" + i).val(),
                            performer: $("#performer_" + i).val(),
                            responsible: $("#responsible_" + i).val(),
                            frequency: $("#frequency_" + i).val(),
                            prev_maintenance_date: $("#prev_maintenance_date_" + i).val(),
                            planned_maintenance_date: $("#planned_maintenance_date_" + i).val(),
                            selectedColor: $("#selectedColor_" + i).val(),
                            materials: $("#materialsTextArea_" + i).val(), // Добавляем данные о расходных материалах
                            types_of_work: typesOfWorkValues,
                            checked: $('#disableInTable_' + i).is(':checked') // Добавляем значение чекбокса "не выводить"
                        };
                        // Добавляем данные в массив servicesData
                        servicesData.push(serviceData);
                    }

                    // // Преобразуем типы работ в строку и добавляем в formData
                    // let typesOfWorkString = JSON.stringify(typesOfWorkByService);
                    // formData.append("types_of_work", typesOfWorkString);
                    // Добавляем массив servicesData в formData
                    formData.append("services", JSON.stringify(servicesData));

                    // Отправляем данные на сервер
                    $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "/save-card-data",
                        data: formData,
                        processData: false, // Не обрабатывать данные
                        contentType: false, // Не устанавливать тип содержимого
                        success: function (response) {
                            // Обработка успешного ответа от сервера (например, отображение сообщения об успешном сохранении)
                            // alert("Данные успешно сохранены!");
                            // console.log(formData);
                            window.location.href = "/home/card-object/" + response.id;
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
@endsection
