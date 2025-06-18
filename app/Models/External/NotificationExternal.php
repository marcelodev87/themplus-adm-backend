<?php

namespace App\Models\External;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class NotificationExternal extends Model
{
    use HasUuid, Notifiable;

    protected $connection = 'external';

    protected $table = 'notifications';

    protected $fillable = [
        'enterprise_id',
        'user_id',
        'read',
        'title',
        'text',
    ];

    public function enterprise()
    {
        return $this->belongsTo(EnterpriseExternal::class, 'enterprise_id');
    }

    public function user()
    {
        return $this->belongsTo(UserExternal::class, 'user_id');
    }
}
