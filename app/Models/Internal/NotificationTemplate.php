<?php

namespace App\Models\Internal;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class NotificationTemplate extends Model
{
    use HasUuid, Notifiable;

    protected $table = 'notification_templates';

    protected $fillable = [
        'title',
        'text',
    ];
}
