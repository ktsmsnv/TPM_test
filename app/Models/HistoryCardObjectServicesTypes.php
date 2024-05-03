<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class HistoryCardObjectServicesTypes extends Eloquent
{
protected $connection = 'mongodb';
protected $collection = 'history_card_object_service_types';
protected $fillable = ['card_id', 'card_services_id', 'type_work'];
    public function cardObjectServices()
    {
        return $this->belongsTo(HistoryCardObjectServices::class, 'card_services_id');
    }
    public function cardObjectMain()
    {
        return $this->belongsTo(HistoryCardObjectMain::class, 'card_id');
    }
}
