{{--страница карточка заказ-наряда --}}
@extends('layouts.app')

@section('content')
    <div class="container custom_tab_style1_outer">
        <div class="row">
            {{-- ЗАГОЛОВОК С ПАНЕЛЬЮ КНОПОК --}}
            <div class="col-md-12 text-left">
                <h2 class="mb-4"><strong>Карточка заказ-наряда</strong></h2>
            </div>
            <div class="btns d-flex mb-5">
                <div class="d-flex gap-2">
                    {{-- <button type="button" class="btn btn-success">Сохранить</button>--}}
                    <button type="button" class="btn btn-secondary me-5">Закрыть</button>

                    <button type="button" class="btn btn-success">Выгрузить PDF</button>
                    <a href="/home/card-object" target="_blank" type="button" class="btn btn-primary me-5">Открыть карточку объекта</a>

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
                                    <button class="btn btn-primary" >Завершить заказ</button>
                                </div>
                                <div class="member-info--inputs d-flex gap-5">
                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Вид инфраструктуры</label>
                                            <input name="" class="form-control w-100" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Наименование объекта</label>
                                            <input name="" class="form-control w-100" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Инв./заводской №</label>
                                            <input class="form-control w-100" name="" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Место установки</label>
                                            <input class="form-control  w-100" name="" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Вид обслуживания</label>
                                            <input class="form-control  w-100" name="" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Плановая дата обслуживания</label>
                                            <input class="form-control w-100" name="" readonly>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column gap-3 w-50">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата создания</label>
                                            <input class="form-control w-100" name="" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Дата последнего сохранения</label>
                                            <input class="form-control w-100" name="" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Фактическая дата</label>
                                            <input class="form-control  w-100" name="" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Исполнитель</label>
                                            <input class="form-control  w-100" name="" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Ответственный</label>
                                            <input class="form-control  w-100" name="" readonly>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <label class="w-100">Статус</label>
                                            <input class="form-control  w-100" name="" readonly>
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
                                        <div class="grid-item">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <label class="form-check-label form-control" for=" ">
                                                    работа 1
                                                </label>
                                            </div>
                                        </div>
                                        <div class="grid-item">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <label class="form-check-label form-control" for=" ">
                                                    работа 2
                                                </label>
                                            </div>
                                        </div>
                                        <div class="grid-item">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <label class="form-check-label form-control" for=" ">
                                                    работа 3
                                                </label>
                                            </div>
                                        </div>
                                        <div class="grid-item">
                                            <div class="form-check d-flex align-items-center gap-2">
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
                                    <textarea class="form-control" readonly></textarea>
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
            });
        </script>
@endsection
