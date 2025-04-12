<?php

namespace App\Http\Resources\External\Subscription;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class SubscriptionTableResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $using = DB::connection('external')
            ->table('enterprises')
            ->where('subscription_id', $this->id)
            ->count();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'enterprises_using' => $using,
        ];
    }
}
