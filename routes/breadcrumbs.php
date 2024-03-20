<?php
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

// Хлебные крошки для Главной страницы
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Реестр объектов', route('home'));
});

// Хлебные крошки для страницы Реестр заказов
Breadcrumbs::for('reestr-work-orders', function ($trail) {
    $trail->push('Реестр заказ-нарядов ТРМ', route('reestr-workOrders'));
});

// Хлебные крошки для страницы Карточка объекта
Breadcrumbs::for('card-object', function ($trail) {
    $trail->parent('home');
    $trail->push('Карточка объекта', route('cardObject'));
});
// Хлебные крошки для страницы СОЗДАНИЕ Карточка объекта
Breadcrumbs::for('card-object-create', function ($trail) {
    $trail->parent('home');
    $trail->push('Создание карточки объекта', route('cardObject-create'));
});
// Хлебные крошки для страницы РЕДАКТИРОВАНИЕ Карточка объекта
Breadcrumbs::for('/card-object/edit', function ($trail) {
    $trail->parent('card-object');
    $trail->push('Редактирование карточки объекта', route('cardObject-edit'));
});

// Хлебные крошки для страницы Карточка заказа
Breadcrumbs::for('card-work-order', function ($trail) {
    $trail->parent('reestr-work-orders');
    $trail->push('Карточка заказ-наряда', route('workOrder'));
});
