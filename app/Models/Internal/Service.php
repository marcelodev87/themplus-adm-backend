<?php

namespace App\Models\Internal;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Service extends Model
{
    use HasUuid, Notifiable;

    protected $connection = 'mysql';

    protected $table = 'services';

    protected $fillable = [
        'name',
        'code_service',
    ];
}
