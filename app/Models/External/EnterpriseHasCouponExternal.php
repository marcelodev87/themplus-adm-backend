<?php

namespace App\Models\External;

use App\Models\Internal\Coupon;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EnterpriseHasCouponExternal extends Model
{
    use HasUuid, Notifiable;

    protected $connection = 'external';

    protected $table = 'enterprise_has_coupons';

    protected $fillable = [
        'enterprise_id',
        'coupon_id',
    ];

    public function coupons()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }
}
