<?php

namespace App\Models\Internal;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Coupon extends Model
{
    use HasUuid, Notifiable;

    protected $connection = 'mysql';

    protected $table = 'coupons';

    protected $fillable = [
        'name',
        'type',
        'service_id',
        'subscription_id',
        'discount',
        'date_expiration',
        'limit',
        'description',
        'active',

    ];
}
