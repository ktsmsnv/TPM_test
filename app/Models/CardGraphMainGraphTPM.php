<?php
// CardObjectMain.php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class CardGraphMainGraphTPM extends Eloquent
{
protected $connection = 'mongodb';
protected $collection = 'card_graph_main_graphtpm';
protected $fillable = ['card_id', 'name_object', 'factory_num', 'january', 'february', 'march', 'april',
    'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
}
