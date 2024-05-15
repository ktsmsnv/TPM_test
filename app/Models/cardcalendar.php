<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model as Eloquent;
class cardcalendar extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'card_calendar';
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
}
