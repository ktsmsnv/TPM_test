{{--страница реестр объектов (главная) --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="reestrObject">
            <div class="reestrObject__btns d-flex justify-content-between mb-5">
                <button type="button" class="btn btn-secondary">Обновить реестр</button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success">Показать активные объекты</button>
                    <button type="button" class="btn btn-primary">Создать карточку объекта</button>
                    <button type="button" class="btn btn-primary btn-primary--2">Скопировать карточку объекта</button>
                    <button type="button" class="btn btn-light">Сформировать график TPM</button>
                    <button type="button" class="btn btn-light">Сформировать календарь TPM</button>
                    <button type="button" class="btn btn-light">Сформировать заказ-наряд TPM</button>
                </div>
            </div>
            <select class="form-control d-none" id="locale">
                <option value="ru-RU">ru-RU</option>
            </select>
            <h3 class="text-center mb-4"><strong>Реестр объектов</strong></h3>
            <div class="card">
                <div class="card-body">
                    <div class="reestrObject__table text-center">
                        <table id="reestrObject" data-toolbar="#toolbar" data-search="true" data-show-refresh="true"
                               data-show-toggle="true" data-show-fullscreen="true" data-show-columns="true"
                               data-show-columns-toggle-all="true" data-show-export="true" data-click-to-select="true"
                               data-minimum-count-columns="12" data-show-pagination-switch="true" data-pagination="true"
                               data-id-field="id" data-response-handler="responseHandler">
                            <thead>
                            <tr>
                                <th colspan="6">Объекты инфраструктуры</th>
                                <th colspan="8">Обслуживание TPM</th>
                            </tr>
                            <tr>
                                <th>Вид инфраструктуры</th>
                                <th>Наименование объекта</th>
                                <th>Дата ввода в эксплуатацию</th>
                                <th>Дата вывода из эксплуатации</th>
                                <th>Дата окончания аттестаци/гарантии</th>
                                <th>Инв./заводской номер</th>
                                <th>Место установки</th>
                                <th>Плановая дата обслуживания</th>
                                <th>Фактическая дата предыдущего обслуживания</th>
                                <th>Вид ближайшего обслуживания</th>
                                <th>Исполнитель</th>
                                <th>Ответственный</th>
                                <th>Заказ-наряд</th>
                                <th>Календарь TPM</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            let $table = $('#reestrObject');
            initTable($table);

            // инициализация таблицы и ее настроек
            function initTable($table) {
                $table.bootstrapTable({
                    locale: $('#locale').val(),
                    pagination: true,
                    pageNumber: 1,
                    pageSize: 5,
                    pageList: [5, 15, 50, 'all'],
                });
            }
        });
    </script>
@endsection

