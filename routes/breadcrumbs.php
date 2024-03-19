<?php
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

// Хлебные крошки для Главной страницы
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Реестр объектов', route('home'));
});

// Хлебные крошки для страницы Реестр заказов
Breadcrumbs::for('reestr-work-orders', function ($trail) {
    $trail->parent('home');
    $trail->push('Реестр заказов', route('reestr-workOrders'));
});

// Хлебные крошки для страницы Карточка объекта
Breadcrumbs::for('card-object', function ($trail) {
    $trail->parent('home');
    $trail->push('Карточка объекта', route('cardObject'));
});

// Хлебные крошки для страницы Карточка заказа
Breadcrumbs::for('card-work-order', function ($trail) {
    $trail->parent('home');
    $trail->push('Карточка заказа', route('workOrder'));
});
