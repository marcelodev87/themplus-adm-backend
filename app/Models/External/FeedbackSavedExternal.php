<?php

namespace App\Models\External;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class FeedbackSavedExternal extends Model
{
    use HasUuid, Notifiable;

    protected $connection = 'external';

    protected $table = 'feedbacks_saved';

    protected $fillable = [
        'user_name',
        'user_email',
        'enterprise_name',
        'message',
        'date_feedback'
    ];
    protected $casts = [
        'date_feedback' => 'datetime',
    ];
}
