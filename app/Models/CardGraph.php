<?php
// CardObjectMain.php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class CardGraph extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'card_graph';

    protected $fillable = [
        'card_id',
        'curator',
        'year_action',
        'date_create',
        'date_last_save',
        'date_archive',
    ];

    // Если в вашей коллекции не используется автоинкрементный id, вы можете отключить его
    public $incrementing = false;

    // Дополнительные настройки для _id
    protected $primaryKey = '_id';
    protected $keyType = 'string';

    // Связь с другими моделями, если это необходимо
    public function cardObjectMain()
    {
        return $this->belongsTo(CardObjectMain::class, 'card_id', '_id');
    }
    public function cardObjectServices()
    {
        return $this->belongsTo(CardObjectServices::class, 'card_id', 'card_object_main_id');
    }

    public function object()
    {
        return $this->hasMany(CardObjectMain::class, 'card_id', '_id');
    }
}
