<?php

namespace App\Models\External;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SettingsCounterExternal extends Model
{
    use HasUuid, Notifiable;

    protected $connection = 'external';

    protected $table = 'settings_counter';

    protected $fillable = [
        'enterprise_id',
        'allow_add_user',
        'allow_edit_user',
        'allow_delete_user',
        'allow_edit_movement',
        'allow_delete_movement',
    ];
}
