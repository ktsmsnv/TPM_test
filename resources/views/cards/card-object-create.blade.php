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
                                            <input name="infrastructure" placeholder="Введите вид инфраструктуры"
                                                   class="form-control w-100">
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
                                            <input class="form-control  w-100" name="location"
                                                   placeholder="Введите место установки">
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
                                    <input type="file" id="docUpload" class="d-none" multiple accept=".pdf, .doc, .docx">
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
                        <input name="" placeholder="Введите название вида работы" class="form-control w-100" id="typeOfWorkInput">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" id="addTypeOfWork">Добавить</button>
                </div>
            </div>
        </div>
    </div>



    {{-- документы --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Обработчик загрузки документов
            $('#docUpload').change(function() {
                let fileList = this.files;
                let documentList = $('#documentList');
                documentList.empty(); // Очищаем список документов перед добавлением новых

                for (let i = 0; i < fileList.length; i++) {
                    let file = fileList[i];
                    let fileName = file.name;
                    let listItem = $('<a>').attr('href', '#').text(fileName);
                    documentList.append(listItem);
                    documentList.append($('<br>'));
                }
            });

            // Обработчик загрузки изображений
            $('#imageUpload').change(function() {
                let fileList = this.files;
                let uploadedImage = $('#uploadedImage');
                if (fileList.length > 0) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        uploadedImage.attr('src', e.target.result);
                    }
                    reader.readAsDataURL(fileList[0]);
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {

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
                                                        <input id="service_type_' + serviceTabsCount + '" name="service_type" placeholder="Введите вид обслуживания" \
                                                            class="form-control w-100"> \
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
                                                        <input id="frequency_' + serviceTabsCount + '" name="frequency" class="form-control w-100" \
                                                            placeholder="Введите периодичность"> \
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
                                                        <input type="hidden" id="selectedColor" name="selectedColor"> \
                                                    </div> \
                                                </div> \
                                            </div> \
                                        </div> \
                                    </div> \
                                    \
                                    \
                                     {{-- ВИДЫ РАБОТ --}}\
                                     <div class="member_card_style types">\
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
                                        <textarea class="form-control" placeholder="Введите расходные материалы и ЗИП"></textarea>\
                                    </div>\
                                    </div>\
                                     </div>\
                                    \
                                    {{-- ИЗОБРАЖЕНИЕ --}}\
                                    <div class="member_card_style image">\
                                        <div class="member-info">\
                                        <div class="d-flex justify-content-between mb-4">\
                                        <h4>Изображение объекта</h4>\
                                         <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#imageDownloadModal">Загрузить</button>\
                                    </div>\
                                    <div class="objectImage">\
                                        <img src="http://placehold.it/350x450"/>\
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
                    // Получаем цвет выбранного блока и устанавливаем его в скрытом поле ввода
                    const selectedColor = $(this).data('color');
                    $('#selectedColor').val(selectedColor);
                });
            }
            // Вызываем функцию для обновления обработчика событий для выбора цвета
            updateColorPicker();


            // Объявляем переменную typesOfWork в глобальной области видимости
            let typesOfWork = [];
            // Обработка клика по кнопке "Добавить вид работы"
            $("#addTypeOfWork").click(function() {
                // Получаем значение вида работы из поля ввода
                let typeOfWork = $("#typeOfWorkInput").val().trim();
                if (typeOfWork !== '') {
                    // Добавляем значение вида работы в массив typesOfWork
                    typesOfWork.push(typeOfWork);

                    // Создаем новый элемент списка для нового вида работы
                    let listItem = '<input name="types_of_work[]" value="' + typeOfWork + '">';
                    // Добавляем скрытое поле с именем "types_of_work[]" и значением вида работы
                    $("#typesOfWork").append(listItem);
                    // Очищаем поле ввода после добавления
                    $("#typeOfWorkInput").val('');
                }
            });


             //------------  обработчик сохранения данных  ------------

            $(".saveCard").click(function() {
                // Создаем объект FormData для отправки данных на сервер, включая файлы
                let formData = new FormData();

                // Собираем данные с основной формы
                formData.append('infrastructure', $("input[name=infrastructure]").val());
                formData.append('name', $("input[name=name]").val());
                formData.append('number', $("input[name=number]").val());
                formData.append('location', $("input[name=location]").val());
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

                // Собираем данные с каждой вкладки обслуживания
                for (let i = 1; i < serviceTabsCount; i++) {
                    let serviceData = {
                        service_type: $("#service_type_" + i).val(),
                        short_name: $("#short_name_" + i).val(),
                        performer: $("#performer_" + i).val(),
                        responsible: $("#responsible_" + i).val(),
                        frequency: $("#frequency_" + i).val(),
                        prev_maintenance_date: $("#prev_maintenance_date_" + i).val(),
                        planned_maintenance_date: $("#planned_maintenance_date_" + i).val(),
                        selectedColor: $("#selectedColor").val(),

                        service_id: i
                    };
                    // Добавляем собранные данные в formData
                    for (let key in serviceData) {
                        formData.append("services[" + i + "][" + key + "]", serviceData[key]);
                    }

                    // Собираем данные о расходных материалах и ЗИП
                    let materials = $("#service_" + i + " .material_text textarea").val();
                    formData.append("services[" + i + "][materials]", materials);

                    for (let j = 0; j < typesOfWork.length; j++) {
                        formData.append("services[" + i + "][types_of_work][" + j + "]", typesOfWork[j]);
                    }
                }

                // Добавляем массив typesOfWork в formData
                // formData.append('types_of_work', JSON.stringify(typesOfWork));


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
                    success: function(response) {
                        // Обработка успешного ответа от сервера (например, отображение сообщения об успешном сохранении)
                        alert("Данные успешно сохранены!");
                    },
                    error: function(error) {
                        // Обработка ошибки при сохранении данных
                        alert("Ошибка при сохранении данных!");
                    }
                });
            });
        });
    </script>
@endsection
