<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CouponResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $total = DB::connection('external')->table('enterprises')->where('coupon_id')->count();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'using' => $total,
        ];
    }
}
