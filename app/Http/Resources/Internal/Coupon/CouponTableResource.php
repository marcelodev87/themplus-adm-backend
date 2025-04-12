<?php

namespace App\Http\Resources\Internal\Coupon;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CouponTableResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $total = DB::connection('external')
            ->table('enterprises')
            ->where('coupon_id', $this->id)
            ->count();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->service,
            'date_expiration' => $this->date_expiration,
            'created_at' => $this->created_at,
            'using' => $total,
        ];
    }
}
