<?php

namespace App\Models\External;

use App\Models\External\SubscriptionExternal;
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
        'code_financial'
    ];

    public function subscription()
    {
        return $this->hasOne(SubscriptionExternal::class, 'subscription_id');
    }

}
