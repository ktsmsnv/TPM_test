@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="reestrObject">
            <div class="reestrObject__btns d-flex justify-content-between">
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
            <div class="reestrObject__table">

            </div>
        </div>
    </div>
@endsection
