<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Foundation\Auth\User as Authenticatable;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use MongoDB\Laravel\Eloquent\Model;

class pageReestrCalendar extends Authenticatable
{
    use HasFactory;

    protected $table = 'reestCalendar';

//Таблица reestrCalendar: 1. Вид инфраструктуры, 2. Наименование объекта, 3. Инв./заводской номер, 4. Место установки,
// 5. Виды обслуживания, 6. Год действия календаря, 7. Дата создания, 8. Дата последнего сохранения, 9. Дата архивации,
// 10. Куратор.
    protected $fillable = ['typeInfrastructCalend', 'nameObjectCalend', 'invFactNum', 'instPlace',
        'typeServ', 'calendarYear', 'dateCreationCalend', 'dateLastSaveCalend', 'dateArchivCalend', 'curatorCalend'];
}
