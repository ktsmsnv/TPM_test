<?php
// CardObjectMain.php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class CardObjectServices extends Eloquent
{
protected $connection = 'mongodb';
protected $collection = 'card_object_services';
protected $fillable = ['card_id',
'type',
'name',
'executor',
'responsible',
'periodicity',
'previous_maintenance_date',
'planned_maintenance_date',
'calendar_color',
'consumable_materials'];
}
