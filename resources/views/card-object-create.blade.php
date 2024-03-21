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
                    <button type="button" class="btn btn-success">Сохранить</button>
                    <a href="/home" type="button" class="btn btn-secondary me-5">Закрыть</a>

                    <button type="button" class="btn btn-primary me-5" data-bs-toggle="modal"
                            data-bs-target="#imageDownloadModal">Загрузить изображение
                    </button>
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
                                    <button class="btn btn-primary">Создать обслуживание</button>
                                </div>
                                <div class="member-info--inputs d-flex gap-5">
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Вид инфраструктуры</label>
                                            <input name="" placeholder="Введите вид инфраструктуры"
                                                   class="form-control w-100">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Наименование объекта</label>
                                            <input name="" placeholder="Введите наименование объекта"
                                                   class="form-control w-100">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Инв./заводской №</label>
                                            <input class="form-control w-100" name=""
                                                   placeholder="Введите инв./заводской №">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Место установки</label>
                                            <input class="form-control  w-100" name=""
                                                   placeholder="Введите место установки">
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата прихода</label>
                                            <input class="form-control w-100" name=""
                                                   placeholder="Введите дату прихода">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата ввода в эксплуатацию</label>
                                            <input class="form-control w-100" name=""
                                                   placeholder="Введите дату ввода в эксплуатацию">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата окончания аттестации/гарантии</label>
                                            <input class="form-control w-100" name=""
                                                   placeholder="Введите дату окончания аттестации/гарантии">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата вывода из эксплуатации</label>
                                            <input class="form-control  w-100" name=""
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
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#docDownloadModal">Вложить документ
                                    </button>
                                </div>
                                <div class="objectDocs">
                                    <a href="">Акт входного контроля Сварочный аппарат полуавтомат.pdf</a>
                                </div>
                            </div>
                        </div>
                        {{-- ИЗОБРАЖЕНИЕ --}}
                        <div class="member_card_style image">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Изображение объекта</h4>
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#imageDownloadModal">Загрузить
                                    </button>
                                </div>
                                <div class="objectImage">
                                    <img src="http://placehold.it/350x450"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ВКЛАДКА "ОБСЛУЖИВАНИЕ" --}}

            </div>

        </div>
    </div>

    <!-- Загрузка изображения модальное окно -->
    <div class="modal fade" id="imageDownloadModal" tabindex="-1" aria-labelledby="imageDownloadModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="imageDownloadModalLabel"><strong>Загрузка изображения
                            объекта</strong></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span>Загрузите изображение:</span>
                    <input class="form-control w-100 mt-2" type="file" accept="image/*, .jpg, .jpeg, .png"
                           title="Выберите изображение в формате .jpg, .jpeg, .png">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary">Загрузить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Загрузка документа модальное окно -->
    <div class="modal fade" id="docDownloadModal" tabindex="-1" aria-labelledby="docDownloadModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="docDownloadModalLabel"><strong>Загрузка документов объекта</strong>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span>Загрузите документ:</span>
                    <input class="form-control w-100 mt-2 mb-3" type="file" multiple="multiple" title="Выберите файлы">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary">Загрузить</button>
                </div>
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
                        <input name="" placeholder="Введите название вида работы" class="form-control w-100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary">Добавить</button>
                </div>
            </div>
        </div>
    </div>


    {{-- вывод вложенных документов --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $("#cardObjectTab").show;
            // модалка документы
            let documentModal = $("#docDownloadModal .modal-body");
            $('input[type=file]').on('change', function () {
                documentModal.append('<div class="docList"><span><strong>Список вложенных файлов:</strong></span> <ul class="mt-1">'); // Открываем список
                for (let i = 0; i < this.files.length; i++) {
                    let doc = this.files[i].name;
                    documentModal.find('ul').append('<div class="d-flex gap-2 justify-content-between align-items-center mb-3">'
                        + '<li>' + doc + '</li>' +
                        '<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Удалить вложенный файл из списка">' +
                        '<i class="bi bi-trash3"></i></button></div>'); // Добавляем файл в список
                }
                documentModal.append('</ul></div>'); // Закрываем список
            });
            $('#docDownloadModal').on('hidden.bs.modal', function () {
                $(this).find('input[type=file]').val(''); // Сброс содержимого input
                let documentModalFiles = $(this).find(".docList");
                documentModalFiles.empty(); // Очистка содержимого модального окна
            });

        });
    </script>

    {{-- динамическое создание вкладок обслуживание --}}
    <script>
        $(document).ready(function () {
            let serviceTabsCount = 1; // начальный счетчик вкладок для обслуживания

            // Обработчик нажатия на кнопку "Создать обслуживание"
            $('button.btn.btn-primary').on('click',
                function () {
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
                                                        <input id="prev_maintenance_date_' + serviceTabsCount + '" name="prev_maintenance_date" class="form-control w-100" \
                                                            placeholder="Введите дату предыдущего обслуживания"> \
                                                    </div> \
                                                    <div class="d-flex justify-content-between align-items-center gap-3"> \
                                                        <label class="w-100" for="planned_maintenance_date_' + serviceTabsCount + '">Плановая дата обслуживания</label> \
                                                        <input id="planned_maintenance_date_' + serviceTabsCount + '" name="planned_maintenance_date" class="form-control w-100" \
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
                                        <div class="typesOfWork">\
                                            <!-- Используем класс row для создания строки -->\
                                            <div class="grid-container">\
                                                <!-- Используем класс col-md-6 для создания двух столбцов на широких экранах -->\
                                                <div class="grid-item">\
                                                    <div class="form-check d-flex align-items-center gap-2">\
                                                        <input class="form-check-input" type="checkbox" value=" " id=" " data-toggle="tooltip" title="нажмите чтобы выбрать">\
                                                            <label class="form-check-label form-control" for=" ">\
                                                                работа 1\
                                                            </label>\
                                                    </div>\
                                                </div>\
                                                <div class="grid-item">\
                                                    <div class="form-check d-flex align-items-center gap-2">\
                                                        <input class="form-check-input" type="checkbox" value=" " id=" " data-toggle="tooltip" title="нажмите чтобы выбрать">\
                                                            <label class="form-check-label form-control" for=" ">\
                                                                работа 2\
                                                            </label>\
                                                    </div>\
                                                </div>\
                                                <div class="grid-item">\
                                                    <div class="form-check d-flex align-items-center gap-2">\
                                                        <input class="form-check-input" type="checkbox" value=" " id=" " data-toggle="tooltip" title="нажмите чтобы выбрать">\
                                                            <label class="form-check-label form-control" for=" ">\
                                                                работа 3\
                                                            </label>\
                                                    </div>\
                                                </div>\
                                                <div class="grid-item">\
                                                    <div class="form-check d-flex align-items-center gap-2">\
                                                        <input class="form-check-input" type="checkbox" value=" " id=" " data-toggle="tooltip" title="нажмите чтобы выбрать">\
                                                            <label class="form-check-label form-control" for=" ">\
                                                                работа 4\
                                                            </label>\
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
        });
    </script>

    {{-- документы --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // модалка документы
            let documentModal = $("#docDownloadModal .modal-body");
            $('input[type=file]').on('change', function () {
                documentModal.append('<div class="docList"><span><strong>Список вложенных файлов:</strong></span> <ul class="mt-1">'); // Открываем список
                for (let i = 0; i < this.files.length; i++) {
                    let doc = this.files[i].name;
                    documentModal.find('ul').append('<div class="d-flex gap-2 justify-content-between align-items-center mb-3">'
                        + '<li>' + doc + '</li>' +
                        '<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Удалить вложенный файл из списка">' +
                        '<i class="bi bi-trash3"></i></button></div>'); // Добавляем файл в список
                }
                documentModal.append('</ul></div>'); // Закрываем список
            });
            $('#docDownloadModal').on('hidden.bs.modal', function () {
                $(this).find('input[type=file]').val(''); // Сброс содержимого input
                let documentModalFiles = $(this).find(".docList");
                documentModalFiles.empty(); // Очистка содержимого модального окна
            });

        });
    </script>
@endsection
