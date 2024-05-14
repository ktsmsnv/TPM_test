<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cardcalendar extends Model
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
}
