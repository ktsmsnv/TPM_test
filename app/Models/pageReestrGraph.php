<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Foundation\Auth\User as Authenticatable;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use MongoDB\Laravel\Eloquent\Model;

class pageReestrGraph extends Authenticatable
{
    use HasFactory;
    protected $table = 'reestGraph';

//Таблица reestrGraph: 1. Вид инфраструктуры, 2. Наименование графика, 3. Год действия, 4. Дата создания, 5. Дата последнего сохранения, 6. Дата архивации, 7. Исполнитель, 8. Ответственный, 9. Куратор.
    protected $fillable = ['typeInfrastruct', 'nameGraph', 'yearAction', 'dateCreation',
        'dateLastSave', 'dateArchiv', 'actor', 'responsible', 'curator'];

}
