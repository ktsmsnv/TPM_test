<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\Model as Eloquent;
class CardObjectServices extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'card_object_services';
    protected $fillable = [
        'card_object_main_id', 'service_type', 'short_name',
        'performer', 'responsible', 'frequency', 'prev_maintenance_date',
        'planned_maintenance_date', 'calendar_color', 'consumable_materials', 'checked'
    ];

    public function cardObjectMain()
    {
        return $this->belongsTo(CardObjectMain::class, 'card_object_main_id', '_id');
    }
    public function services_types()
    {
        return $this->hasMany(CardObjectServicesTypes::class, 'card_services_id', '_id');
    }
    // Добавляем отношение к заказам наряда
    public function cardWorkOrders()
    {
        return $this->hasMany(CardWorkOrder::class, 'card_object_services_id');
    }

    public function cardGraph()
    {
        return $this->belongsTo(CardGraph::class, 'card_object_main_id', 'cards_ids');
    }


    public function calculateNextPlannedDate()
    {
        $prevMaintenanceDate = Carbon::parse($this->prev_maintenance_date);
        $frequency = $this->frequency;
        $plannedMaintenanceDate = Carbon::parse($this->planned_maintenance_date);
        $dayOfWeek = $plannedMaintenanceDate->dayOfWeek; // Используем день недели из текущей плановой даты

        switch ($frequency) {
            case 'Ежемесячное':
                $nextDate = $prevMaintenanceDate->addMonth();
                break;
            case 'Ежеквартальное':
                $nextDate = $prevMaintenanceDate->addMonths(3);
                break;
            case 'Полугодовое':
                $nextDate = $prevMaintenanceDate->addMonths(6);
                break;
            case 'Ежегодное':
                $nextDate = $prevMaintenanceDate->addYear();
                break;
            default:
                throw new \Exception('Unknown frequency type');
        }

        // Переносим дату на ближайший нужный день недели
        while ($nextDate->dayOfWeek !== $dayOfWeek) {
            $nextDate->addDay();
        }

        $this->planned_maintenance_date = $nextDate->format('Y-m-d');
        $this->save();
    }
}
