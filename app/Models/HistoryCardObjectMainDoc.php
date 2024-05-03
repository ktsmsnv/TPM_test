<?php
// CardObjectMainDoc.php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class HistoryCardObjectMainDoc extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'history_card_object_main_docs';
    protected $fillable = ['card_id', 'name', 'content'];
    public function cardObjectMain()
    {
        return $this->belongsTo(HistoryCardObjectMain::class, 'card_id', '_id');
    }
}

