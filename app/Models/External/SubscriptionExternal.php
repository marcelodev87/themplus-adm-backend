<?php

namespace App\Models\External;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SubscriptionExternal extends Model
{
    use HasUuid, Notifiable;

    protected $connection = 'external';

    protected $table = 'subscriptions';

    protected $fillable = [
        'price',
    ];
}
