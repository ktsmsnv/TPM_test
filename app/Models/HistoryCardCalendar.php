<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model as Eloquent;

class HistoryCardCalendar extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'history_card_calendar';
    protected $fillable = [
        'card_id',
        'date_create',
//        'date_last_save',
        'date_archive',
        'year',
    ];
    protected $attributes = [
        'card_id' => null,
        'date_create' => null,
        'date_archive' => null,
        'year' => null,
    ];

    public function originalCardCalendar()
    {
        return $this->belongsTo(cardCalendar::class, 'card_calendar_id', '_id');
    }
}
