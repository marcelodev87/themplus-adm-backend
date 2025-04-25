<?php

namespace App\Models\External;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class CategoryExternal extends Model
{
    use HasUuid, Notifiable;

    protected $connection = 'external';

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'type',
        'enterprise_id',
        'alert',
        'active',
        'default',
    ];
}
