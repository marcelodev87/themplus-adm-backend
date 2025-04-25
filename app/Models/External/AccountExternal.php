<?php

namespace App\Models\External;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class AccountExternal extends Model
{
    use HasUuid, Notifiable;

    protected $connection = 'external';

    protected $table = 'accounts';

    protected $fillable = [
        'name',
        'balance',
        'enterprise_id',
        'agency_number',
        'account_number',
        'description',
        'active',
    ];
}
