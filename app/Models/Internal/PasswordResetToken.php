<?php

namespace App\Models\Internal;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PasswordResetToken extends Model
{
    use HasUuid, Notifiable;

    protected $table = 'password_reset_tokens';

    protected $fillable = [
        'email',
        'code',
    ];
}
