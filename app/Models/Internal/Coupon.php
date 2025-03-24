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
        'movements',
        'allow_financial',
        'allow_members',
        'allow_assistant_whatsapp',
        'discount',
        'date_expires',
    ];
}
