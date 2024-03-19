{{--страница карточка объекта --}}
@extends('layouts.app')

@section('content')
    <div class="container custom_tab_style1_outer">
        <div class="row">
            {{-- ЗАГОЛОВОК С ПАНЕЛЬЮ КНОПОК --}}
            <div class="col-md-12 text-left">
                <h2 class="mb-4"><strong>Карточка объекта</strong></h2>
            </div>
            <div class="btns d-flex mb-5">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success">Сохранить</button>
                    <button type="button" class="btn btn-secondary me-5">Закрыть</button>

                    <button type="button" class="btn btn-primary">Скопировать карточку объекта</button>
                    <button type="button" class="btn btn-primary">Загрузить изображение</button>
                </div>
            </div>

            {{-- КАРТОЧКА С ВКЛАДКАМИ --}}
            <ul class="nav nav-tabs custom_tab_style1" id="carObjectTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="main-tab" data-bs-toggle="tab" data-bs-target="#main"
                            type="button" role="tab" aria-controls="main" aria-selected="true">ОСНОВНАЯ
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="service_1-tab" data-bs-toggle="tab" data-bs-target="#service_1"
                            type="button" role="tab" aria-controls="service_1" aria-selected="false">ОБСЛУЖИВАНИЕ 1
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="carObjectTabContent">
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
                                    <button class="btn btn-primary">Вложить документ</button>
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
                                    <button class="btn btn-primary">Загрузить</button>
                                </div>
                                <div class="objectImage">
                                    <img src="http://placehold.it/350x450"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ВКЛАДКА "ОБСЛУЖИВАНИЕ" --}}
                <div class="tab-pane fade" id="service_1" role="tabpanel" aria-labelledby="service_1-tab">
                    <div id="service__blocks" class="d-grid">
                        {{-- ОБСЛУЖИВАНИЕ ТРМ --}}
                        <div class="member_card_style services">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Обслуживание ТРМ</h4>
                                    <button class="btn btn-primary">Обновить даты</button>
                                    <div>
                                        <input type="checkbox" class="form-check-input me-1" id="disableInTable">
                                        <label class="form-check-label disableInTable" for="disableInTable">Не выводить
                                            на основной
                                            экран, в график TPM и не отправлять уведомления</label>
                                    </div>
                                </div>
                                <div class="member-info--inputs d-flex gap-5">
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Вид обслуживания</label>
                                            <input name="" placeholder="Введите вид обслуживания"
                                                   class="form-control w-100">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Сокращенное название</label>
                                            <input name="" placeholder="Введите сокращенное название"
                                                   class="form-control w-100">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Исполнитель</label>
                                            <input class="form-control w-100" name=""
                                                   placeholder="Введите исполнителя">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Ответственный</label>
                                            <input class="form-control  w-100" name=""
                                                   placeholder="Введите ответственного">
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Периодичность</label>
                                            <input class="form-control w-100" name=""
                                                   placeholder="Введите периодичность">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата предыдущего обслуживания</label>
                                            <input class="form-control w-100" name=""
                                                   placeholder="Введите дату предыдущего обслуживания">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Плановая дата обслуживания</label>
                                            <input class="form-control w-100" name=""
                                                   placeholder="Введите плановую дату обслуживания">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Цвет в календаре</label>
                                            <div class="color-options">
                                                <div class="color-option red" data-color="#ff0000"></div>
                                                <div class="color-option green" data-color="#00ff00"></div>
                                                <div class="color-option blue" data-color="#0000ff"></div>
                                            </div>
                                            <input type="hidden" id="selectedColor" name="selectedColor">
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
                                    <button class="btn btn-primary">Добавить вид работ</button>
                                </div>
                                <div class="typesOfWork">
                                    <!-- Используем класс row для создания строки -->
                                    <div class="grid-container">
                                        <!-- Используем класс col-md-6 для создания двух столбцов на широких экранах -->
                                        <div class="grid-item">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input class="form-check-input" type="checkbox" value=" " id=" ">
                                                <label class="form-check-label form-control" for=" ">
                                                    работа 1
                                                </label>
                                            </div>
                                        </div>
                                        <div class="grid-item">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input class="form-check-input" type="checkbox" value=" " id=" ">
                                                <label class="form-check-label form-control" for=" ">
                                                    работа 2
                                                </label>
                                            </div>
                                        </div>
                                        <div class="grid-item">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input class="form-check-input" type="checkbox" value=" " id=" ">
                                                <label class="form-check-label form-control" for=" ">
                                                    работа 3
                                                </label>
                                            </div>
                                        </div>
                                        <div class="grid-item">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input class="form-check-input" type="checkbox" value=" " id=" ">
                                                <label class="form-check-label form-control" for=" ">
                                                    работа 4
                                                </label>
                                            </div>
                                        </div>
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
                                    <!-- Добавляем textarea с атрибутом placeholder -->
                                    <textarea class="form-control" placeholder="Введите расходные материалы и ЗИП"></textarea>
                                </div>
                            </div>
                        </div>
                        {{-- ИЗОБРАЖЕНИЕ --}}
                        <div class="member_card_style image">
                            <div class="member-info">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>Изображение объекта</h4>
                                    <button class="btn btn-primary">Загрузить</button>
                                </div>
                                <div class="objectImage">
                                    <img src="http://placehold.it/350x450"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <script>
            $(document).ready(function () {
                $("#carObjectTab").show;

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
            });
        </script>
@endsection
