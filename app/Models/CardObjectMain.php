<?php
// CardObjectMain.php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class CardObjectMain extends Eloquent
{
protected $connection = 'mongodb';
protected $collection = 'card_object_main';
protected $fillable = ['infrastructure', 'name', 'number', 'location', 'date_arrival', 'date_usage', 'date_cert_end', 'date_usage_end', 'image'];
}
