<?php

namespace App\Models\External;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class FeedbackExternal extends Model
{
    use HasUuid, Notifiable;

    protected $connection = 'external';

    protected $table = 'feedbacks';

    protected $fillable = [
        'user_id',
        'enterprise_id',
        'message',
    ];

    public function externalUser()
    {
        return $this->belongsTo(UserExternal::class, 'user_id');
    }

    public function externalEnterprise()
    {
        return $this->belongsTo(EnterpriseExternal::class, 'enterprise_id');
    }
}
