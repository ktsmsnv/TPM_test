<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class CardObjectServicesTypes extends Eloquent
{
protected $connection = 'mongodb';
protected $collection = 'card_object_service_types';
protected $fillable = ['card_id', 'card_services_id', 'type_work'];
    public function cardObjectServices()
    {
        return $this->belongsTo(CardObjectServices::class, 'card_services_id');
    }
    public function cardObjectMain()
    {
        return $this->belongsTo(CardObjectMain::class, 'cards_ids');
    }
}
