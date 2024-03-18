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
            <div class="reestrGraphObject__table">

            </div>
        </div>
    </div>
@endsection
