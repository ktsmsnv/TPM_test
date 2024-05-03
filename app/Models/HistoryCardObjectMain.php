<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
class HistoryCardObjectMain extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'history_card_object_main';
    protected $fillable = ['infrastructure', 'name', 'number', 'location', 'date_arrival', 'date_usage', 'date_cert_end', 'date_usage_end', 'image'];

    public function services()
    {
        return $this->hasMany(HistoryCardObjectServices::class, 'card_object_main_id', '_id');
    }
    public function documents()
    {
        return $this->hasMany(HistoryCardObjectMainDoc::class, 'card_object_main_id', '_id');
    }
    public function workOrders()
    {
        return $this->hasMany(HistoryCardWorkOrder::class, 'card_id', '_id');
    }
    public function originalCardObject()
    {
        return $this->belongsTo(CardObjectMain::class, 'card_id', '_id');
    }
}
