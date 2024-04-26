<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model as Eloquent;

class CardWorkOrder extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'card_work_order';
    protected $fillable = [
        'card_id',
        'card_object_services_id', // Добавляем новое поле для связи с объектом обслуживания
        'date_create',
//        'date_last_save',
        'date_fact',
        'status',
    ];

    // Определяем связь с объектом обслуживания (CardObjectServices)
    public function cardObjectServices()
    {
        return $this->belongsTo(CardObjectServices::class, 'card_object_services_id', '_id');
    }
}
