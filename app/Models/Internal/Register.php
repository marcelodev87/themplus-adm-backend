<?php

namespace App\Models\Internal;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Register extends Model
{
    use HasUuid, Notifiable;

    protected $table = 'registers';

    protected $fillable = [
        'user_id',
        'action',
        'target',
        'identification',
        'date_register',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
