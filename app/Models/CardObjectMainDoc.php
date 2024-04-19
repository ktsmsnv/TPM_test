<?php
// CardObjectMainDoc.php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class CardObjectMainDoc extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'card_object_main_docs';
    protected $fillable = ['card_object_main_id', 'name', 'content'];
    public function cardObjectMain()
    {
        return $this->belongsTo(CardObjectMain::class, 'card_object_main_id', '_id');
    }
}

