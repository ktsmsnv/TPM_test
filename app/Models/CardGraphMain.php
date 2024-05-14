<?php
// CardObjectMain.php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class CardGraphMain extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'card_graph_main_collection';
    protected $fillable = ['infrastructure', 'curator', 'year_action', 'date_create', 'date_last_save', 'date_archive'];

    public function graphTPM()
    {
        return $this->hasMany(CardGraphMainGraphTPM::class, 'card_graph_main_collection_id', '_id');
    }
}
