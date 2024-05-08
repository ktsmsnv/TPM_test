<?php
// CardObjectMain.php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class CardGraph extends Eloquent
{
    protected $table = 'card_graph_table';

    protected $fillable = [
        'name',
        'infrastructure_type',
        'curator',
        'year_action',
        'date_create',
        'date_last_save',
        'date_archive',
        'cards_ids',
    ];

    protected $casts = [
        'cards_ids' => 'array',
    ];
    // Если в вашей коллекции не используется автоинкрементный id, вы можете отключить его
    public $incrementing = false;

    // Дополнительные настройки для _id
    protected $primaryKey = '_id';
    protected $keyType = 'string';

    // Связь с другими моделями, если это необходимо

    // ЭТА ЧАСТЬ НЕОБХОДИМА ДЛЯ PageReestrGraph
    //---------------------------------------------------------------------------------
    public function cardObjectMain()
    {
        return $this->hasMany(CardObjectMain::class, 'cards_ids', '_id');
    }
//    public function cardObjectServices()
//    {
//        return $this->belongsTo(CardObjectServices::class, 'cards_ids', 'card_object_main_id');
//    }
    public function cardObjectServices()
    {
        return $this->hasMany(CardObjectServices::class, 'cards_ids', 'card_object_main_id');
    }
    //---------------------------------------------------------------------------------

    public function graph()
    {
        return $this->hasMany(CardGraph::class, '_id', 'cards_ids');
    }

    public function object()
    {
        return $this->hasMany(CardObjectMain::class, '_id', 'cards_ids');
    }

    public function services()
    {
        return $this->hasMany(CardObjectServices::class, 'card_object_main_id', 'cards_ids');
    }
}
