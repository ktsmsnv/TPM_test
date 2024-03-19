@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="reestrGraphObject">
            <div class="reestrGrapthObject__btns d-flex justify-content-between">
                <button type="button" class="btn btn-secondary">Обновить реестр</button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success">Выбрать период действия</button>
                    <button type="button" class="btn btn-success">Показать активные графики</button>
                    <button type="button" class="btn btn-primary">Реестр графиков ТРМ</button>
                </div>
            </div>
            <select class="form-control d-none" id="locale">
                <option value="ru-RU">ru-RU</option>
            </select>

            <div class="reestrGraphObject__table">
                <div class="tab-pane fade show active" id="contractStorage" role="tabpanel" aria-labelledby="contractStorage-tab">
                    <div class="card-body">
                        <table id="reestrGraphTable" data-toolbar="#toolbar" data-search="true" data-show-refresh="true"
                               data-show-toggle="true" data-show-fullscreen="true" data-show-columns="true"
                               data-show-columns-toggle-all="true" data-detail-view="true" data-show-export="true"
                               data-click-to-select="true" data-detail-formatter="detailFormatter"
                               data-minimum-count-columns="12" data-show-pagination-switch="true" data-pagination="true"
                               data-id-field="id" data-response-handler="responseHandler">
                            <thead>
                            <tr>
{{--                                <th>id</th>--}}
                                <th>Вид инфраструктуры</th>
                                <th>Наименование графика</th>
                                <th>Год действия</th>
                                <th>Дата создания</th>
                                <th>Дата последнего сохранения</th>
                                <th>Дата архивации</th>
                                <th>Исполнитель</th>
                                <th>Ответственный</th>
                                <th>Куратор</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($pageReestrGraph as $item)
                                <tr class="editable-row" data-id="{{ $item->id }}">
{{--                                    <td>{{ $item->id }}</td>--}}
                                    <td>{{ $item->typeInfrastruct }}</td>
                                    <td>{{ $item->nameGraph }}</td>
                                    <td>{{ year($item->yearAction) }}</td>
                                    <td>{{ date('d.m.Y', strtotime($item->dateCreation)) }}</td>
                                    <td>{{ date('d.m.Y', strtotime($item->dateLastSave)) }}</td>
                                    <td>{{ date('d.m.Y', strtotime($item->dateArchiv)) }}</td>
                                    <td>{{ $item->actor }}</td>
                                    <td>{{ $item->responsible }}</td>
                                    <td>{{ $item->curator }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var $table = $('#reestrGraphTable');
            initTable($table);
            // инициализация таблицы и ее настроек
            function initTable($table) {
                $table.bootstrapTable({
                    locale: $('#locale').val(),
                    pagination: true,
                    pageNumber: 1,
                    pageSize: 5,
                    pageList: [5, 15, 50, 'all'],
                    columns: [
                        // {
                        //     field: 'id',
                        //     title: '№',
                        //     valign: 'middle',
                        //     sortable: true,
                        // },
                        {
                            field: 'typeInfrastruct',
                            title: 'Вид инфраструктуры',
                            valign: 'middle',
                            sortable: true,
                        },
                        {
                            field: 'nameGraph',
                            title: 'Наименование графика',
                            valign: 'middle',
                            sortable: true
                        },
                        {
                            field: 'yearAction',
                            title: 'Год действия',
                            valign: 'middle',
                            sortable: true
                        },
                        {
                            field: 'dateCreation',
                            title: 'Дата создания',
                            valign: 'middle',
                            sortable: true
                        },
                        {
                            field: 'dateLastSave',
                            title: 'Дата последнего сохранения',
                            valign: 'middle',
                            sortable: true
                        },
                        {
                            field: 'dateArchiv',
                            title: 'Дата архивации',
                            valign: 'middle',
                            sortable: true
                        },
                        {
                            field: 'actor',
                            title: 'Исполнитель',
                            valign: 'middle',
                            sortable: true
                        },
                        {
                            field: 'responsible',
                            title: 'Ответственный',
                            valign: 'middle',
                            sortable: true
                        },
                        {
                            field: 'curator',
                            title: 'Куратор',
                            valign: 'middle',
                            sortable: true
                        }
                    ]
                });
            }
            // Передача данных
            // $(document).on('click', '.editCSModal', function() {
            //     var id = $(this).data('id');
            //     var kpId = $(this).data('kp-id');
            //     $('#selectedRecordId').val(id);
            //
            //     // AJAX запрос для получения данных выбранной записи
            //     $.ajax({
            //         url: '/get-cs-details/' + id,
            //         type: 'GET',
            //         success: function(response) {
            //             // console.log(response);
            //             // Заполнение полей формы данными из ответа
            //             $('#contractNameDisplay').text(response.contractName); // Устанавливаем номер проекта
            //             $('#contractName').val(response.contractName);
            //             $('#contractor').val(response.contractor);
            //             $('#dateStart').val(response.dateStart);
            //             $('#dateEnd').val(response.dateEnd);
            //             $('#daysLeft').val(response.daysLeft);
            //
            //             // Вывод дополнительных файлов
            //             var additionalFilesCSHtml = '';
            //             if (response.additionalFilesCS.length > 0) {
            //                 $.each(response.additionalFilesCS, function(index, file) {
            //                     additionalFilesCSHtml += '<li class="mb-2">';
            //                     additionalFilesCSHtml += '<a href="' + file.url +
            //                         '" download id="additionalFileCSName_' + file.id +
            //                         '">' + file.name + '</a>';
            //                     additionalFilesCSHtml += '<label for="additionalFileCS_' +
            //                         file.id + '" class="btn btn-sm btn-danger ms-3">';
            //                     additionalFilesCSHtml += 'Заменить файл';
            //                     additionalFilesCSHtml +=
            //                         '<input type="file" class="form-control additionalFileCS" name="additionalFileCS_' +
            //                         file.id + '" id="additionalFileCS_' + file.id +
            //                         '" data-file-id="' + file.id +
            //                         '" style="display: none;">';
            //                     additionalFilesCSHtml += '</label>';
            //                     // Добавляем кнопку удаления файла
            //                     additionalFilesCSHtml +=
            //                         '<button class="btn btn-sm btn-secondary ms-3 deleteFileButton" data-file-id="' +
            //                         file.id + '">Удалить файл</button>';
            //                     additionalFilesCSHtml += '</li>';
            //                 });
            //             } else {
            //                 additionalFilesCSHtml = 'Нет дополнительных файлов';
            //             }
            //             $('#additionalFilesCS').html(additionalFilesCSHtml);
            //         },
            //         error: function() {
            //             alert('Ошибка при загрузке данных');
            //         }
            //     });
            // });
            // Обработчик события изменения дополнительного файла

            // Обработчик события для кнопки удаления дополнительного файла
            // $(document).on('click', '.deleteFileButton', function() {
            //     event.preventDefault();
            //     var fileId = $(this).data('file-id'); // Получаем ID файла
            //     // Отправляем запрос на сервер для удаления файла
            //     $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         }
            //     });
            //
            //     $.ajax({
            //         url: '/delete-cs-additionalfile/' + fileId,
            //         type: 'DELETE',
            //         success: function(response) {
            //             $('#additionalFileCSName_' + fileId).closest('li')
            //                 .remove(); // Удаляем соответствующий элемент из DOM
            //         },
            //         error: function() {
            //             alert('Ошибка при удалении файла');
            //         }
            //     });
            // });

            // Обработчик события отправки формы
            // $('#editCSFormModal').on('submit', function(event) {
            //     event.preventDefault(); // Предотвращаем отправку формы по умолчанию
            //
            //     // Создаем объект FormData и добавляем данные формы
            //     var formData = new FormData(this);
            //
            //     // Добавляем токен CSRF в данные формы
            //     var csrfToken = $('meta[name="csrf-token"]').attr('content');
            //     formData.append('_token', csrfToken);
            //
            //     // Получаем все выбранные дополнительные файлы и добавляем их в FormData
            //     $('.additionalFileCS').each(function() {
            //         var files = $(this)[0].files;
            //         for (var i = 0; i < files.length; i++) {
            //             formData.append('additionalContracts[]', files[i]);
            //         }
            //     });
            //
            //     // Отправляем ID выбранной записи вместе с данными формы
            //     var selectedRecordId = $('#selectedRecordId').val();
            //     formData.append('selectedRecordId', selectedRecordId);
            //
            //     // Отправляем AJAX запрос
            //     $.ajax({
            //         // url: $(this).attr('action'),
            //         url: '/contract-storage/' + selectedRecordId,
            //         type: $(this).attr('method'),
            //         data: formData,
            //         processData: false,
            //         contentType: false,
            //         success: function(response) {
            //             // Обработка успешного ответа
            //             // console.log(response);
            //             window.location.href = '/contract-storage';
            //         },
            //         error: function(xhr, status, error) {
            //             // Обработка ошибок
            //             console.error(xhr.responseText);
            //         }
            //     });
            // });

            // Удаление договора (cs)
            // let deleteItemId;
            // // Получаем id Договора при открытии модального окна
            // $('#confirmDeleteCS').on('show.bs.modal', function(event) {
            //     deleteItemId = $(event.relatedTarget).data('id');
            // });
            // var csrfToken = $('meta[name="csrf-token"]').attr('content');
            // // Обработчик кнопки удаления
            // $('#confirmDelete').click(function() {
            //     $.ajax({
            //         headers: {
            //             'X-CSRF-TOKEN': csrfToken
            //         },
            //         method: 'DELETE',
            //         url: '/delete-cs/' + deleteItemId,
            //         success: function(response) {
            //             // Обновление страницы или другие действия по желанию
            //             location.reload();
            //         },
            //         error: function(xhr, status, error) {
            //             console.error(xhr.responseText);
            //             // Вывод сообщения об ошибке или другие действия по желанию
            //         }
            //     });
            //     $('#confirmDeleteCS').modal('hide');
            // });

        });

    </script>
@endsection
