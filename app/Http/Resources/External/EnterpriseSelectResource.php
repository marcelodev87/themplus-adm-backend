<?php

namespace App\Http\Resources\External;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnterpriseSelectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
