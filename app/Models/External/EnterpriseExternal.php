<?php

namespace App\Models\External;

use App\Models\Internal\Coupon;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EnterpriseExternal extends Model
{
    use HasUuid, Notifiable;

    protected $connection = 'external';

    protected $table = 'enterprises';

    protected $fillable = [
        'name',
        'cnpj',
        'cpf',
        'cep',
        'state',
        'city',
        'neighborhood',
        'address',
        'complement',
        'email',
        'phone',
        'subscription_id',
        'number_address',
        'created_by',
        'position',
        'counter_enterprise_id',
        'code_financial',
        'coupon_id',
    ];

    public function subscription()
    {
        return $this->belongsTo(SubscriptionExternal::class, 'subscription_id');
    }

    public function coupons()
    {
        return $this->hasMany(EnterpriseHasCouponExternal::class, 'enterprise_id');
    }
}
