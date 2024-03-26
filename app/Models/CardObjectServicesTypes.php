<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class CardObjectServicesTypes extends Eloquent
{
protected $connection = 'mongodb';
protected $collection = 'card_object_services_types';
protected $fillable = ['card_id', 'type_work', 'name_work'];
}
