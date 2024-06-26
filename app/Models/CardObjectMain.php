<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
class CardObjectMain extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'card_object_main';
    protected $fillable = [
        'infrastructure', 'curator', 'name',
        'number', 'location', 'date_arrival', 'date_usage',
        'date_cert_end', 'date_usage_end', 'image'
    ];

    public function services()
    {
        return $this->hasMany(CardObjectServices::class, 'card_object_main_id', '_id');
    }
    public function services_types()
    {
        return $this->hasMany(CardObjectServicesTypes::class, 'card_services_id', '_id');
    }
    public function documents()
    {
        return $this->hasMany(CardObjectMainDoc::class, 'card_object_main_id', '_id');
    }
    public function workOrders()
    {
        return $this->hasMany(CardWorkOrder::class, 'card_id', '_id');
    }
    public function calendar()
    {
        return $this->hasMany(CardCalendar::class, 'card_id', '_id');
    }
    public function graph()
    {
        return $this->hasMany(CardGraph::class, 'cards_ids', '_id');
    }
}
