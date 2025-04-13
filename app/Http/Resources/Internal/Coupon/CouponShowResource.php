<?php

namespace App\Http\Resources\Internal\Coupon;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponShowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'service' => $this->service,
            'discount' => $this->discount,
            'limit' => $this->limit,
            'subscription_id' => $this->subscription_id,
            'date_expiration' => $this->date_expiration,
            'created_at' => $this->created_at,
        ];
    }
}
