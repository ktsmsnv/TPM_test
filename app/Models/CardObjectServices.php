<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class CardObjectServices extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'card_object_services';
    protected $fillable = ['card_object_main_id', 'type', 'name', 'executor', 'responsible', 'periodicity', 'previous_maintenance_date', 'planned_maintenance_date', 'calendar_color', 'consumable_materials'];

    public function cardObjectMain()
    {
        return $this->belongsTo(CardObjectMain::class, 'card_object_main_id', '_id');
    }
    public function services_types()
    {
        return $this->hasMany(CardObjectServicesTypes::class, 'card_services_id', '_id');
    }
}
