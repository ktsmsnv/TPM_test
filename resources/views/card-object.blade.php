{{--страница карточка объекта --}}
@extends('layouts.app')

@section('content')
    <div class="container custom_tab_style1_outer">
        <div class="row">
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

            <div class="col-md-12">
                <ul class="nav nav-tabs custom_tab_style1" id="myTab" role="tablist">
                    <li class="nav-item" role="main">
                        <button class="nav-link active" id="main-tab" data-bs-toggle="tab" data-bs-target="#main" type="button" role="tab" aria-controls="main" aria-selected="true">Основная</button>
                    </li>
                    <li class="nav-item" role="service_1">
                        <button class="nav-link" id="service_1-tab" data-bs-toggle="tab" data-bs-target="#service_1" type="button" role="tab" aria-controls="service_1" aria-selected="false">Обслуживание 1</button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    {{-- ОСНОВНАЯ --}}
                    <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
                        {{-- ОБЩИЕ ДАННЫЕ --}}
                        <div class="member_card_style general">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-8 d-flex align-items-center">
                                            <div class="member-info">
                                                <h4>Общие данные</h4>
                                                <div class="d-grid mt-4">
                                                    <div>
                                                        <label>Вид инфраструктуры</label>
                                                        <input name="" placeholder="Введите вид инфраструктуры">
                                                    </div>
                                                    <div>
                                                        <label>Наименование объекта</label>
                                                        <input name="" placeholder="Введите наименование объекта">
                                                    </div>
                                                    <div>
                                                        <label>Инв./заводской №</label>
                                                        <input name="" placeholder="Введите инв./заводской №">
                                                    </div>
                                                    <div>
                                                        <label>Вид инфраструктуры</label>
                                                        <input name="" placeholder="Введите вид инфраструктуры">
                                                    </div>
                                                    <div>
                                                        <label>Дата прихода</label>
                                                        <input name="" placeholder="Введите дату прихода">
                                                    </div>
                                                    <div>
                                                        <label>Дата ввода в эксплуатацию</label>
                                                        <input name="" placeholder="Введите дату ввода в эксплуатацию">
                                                    </div>
                                                    <div>
                                                        <label>Дата окончания аттестации/гарантии</label>
                                                        <input name="" placeholder="Введите дату окончания аттестации/гарантии">
                                                    </div>
                                                    <div>
                                                        <label>Дата вывода из эксплуатации</label>
                                                        <input name="" placeholder="Введите дату вывода из эксплуатации">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ДОКУМЕНТАЦИЯ --}}
                        <div class="member_card_style">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-8 d-flex align-items-center">
                                            <div class="member-info">
                                                <h4>Документация</h4>
                                                <a href="#">Акт входного контроля Сварочный аппарат полуавтомат.pdf</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4"><img class="img-fluid" src="https://picsum.photos/id/1068/400/300" alt="" /></div>
                            </div>
                        </div>
                    </div>
                    {{-- ОБСЛУЖИВАНИЕ --}}
                    <div class="tab-pane fade" id="service_1" role="tabpanel" aria-labelledby="service_1-tab">
                        <div class="member_card_style">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-8 d-flex align-items-center">
                                            <div class="member-info">
                                                <h4>Lorem ipsum dolor</h4>
                                                <span>Chief Executive Officer</span>
                                                <p>
                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Odio reiciendis ipsa nulla magnam eligendi odit laudantium in voluptatum reprehenderit qui adipisci aliquam, doloribus quisquam facere dolore soluta, dolorem,
                                                    illum quis.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4"><img class="img-fluid" src="https://picsum.photos/id/1068/400/300" alt="" /></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $("#mytab").show
    </script>
@endsection
