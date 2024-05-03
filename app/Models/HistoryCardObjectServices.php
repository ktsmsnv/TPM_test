<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
class HistoryCardObjectServices extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'history_card_object_services';
    protected $fillable = ['card_object_main_id', 'service_type', 'short_name', 'performer', 'responsible', 'frequency', 'prev_maintenance_date', 'planned_maintenance_date', 'calendar_color', 'consumable_materials', 'checked'];

    public function cardObjectMain()
    {
        return $this->belongsTo(HistoryCardObjectMain::class, 'card_object_main_id', '_id');
    }
    public function services_types()
    {
        return $this->hasMany(HistoryCardObjectServicesTypes::class, 'card_services_id', '_id');
    }
    // Добавляем отношение к заказам наряда
    public function cardWorkOrders()
    {
        return $this->hasMany(HistoryCardWorkOrder::class, 'card_object_services_id');
    }
}
