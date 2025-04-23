<?php

namespace App\Http\Resources\Internal\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceSelectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'label' => $this->name,
            'value' => $this->code_service,
        ];
    }
}
