<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model as Eloquent;

class HistoryCardGraph extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'history_card_graph_table';
    protected $fillable = [
        'name',
        'infrastructure_type',
        'curator',
        'year_action',
        'date_create',
        'date_last_save',
        'date_archive',
        'cards_ids',
//        'card_graph_id',
    ];
    protected $casts = [
        'cards_ids' => 'array',
    ];
    // Если в вашей коллекции не используется автоинкрементный id, вы можете отключить его
    public $incrementing = false;

    // Дополнительные настройки для _id
    protected $primaryKey = '_id';
    protected $keyType = 'string';

    public function originalCardGraph()
    {
        return $this->belongsTo(CardGraph::class, 'card_graph_id', '_id');
    }

    public function cardObjectMain()
    {
        return $this->hasMany(CardObjectMain::class, 'cards_ids', '_id');
    }
    public function cardObjectServices()
    {
        return $this->hasMany(CardObjectServices::class, 'cards_ids', 'card_object_main_id');
    }
    public function graph()
    {
        return $this->hasMany(CardGraph::class, '_id', 'cards_ids');
    }

    public function object()
    {
        return $this->hasMany(CardObjectMain::class, '_id', 'cards_ids');
        return $this->hasMany(CardObjectServices::class, 'card_object_main_id', 'cards_ids');
    }

    public function services()
    {
        return $this->hasMany(CardObjectServices::class, 'card_object_main_id', 'cards_ids');
    }
}
