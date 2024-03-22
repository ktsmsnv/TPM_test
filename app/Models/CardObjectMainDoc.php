<?php
// CardObjectMainDoc.php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class CardObjectMainDoc extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'card_object_main_docs';
    protected $fillable = ['card_id', 'name', 'content'];
}
