<?php

namespace App\Http\Resources\Internal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'position' => $this->position,
            'active' => $this->active,
            'created_by' => $this->created_by,
            'department_id' => $this->department_id,
            'department' => $this->department,
            'created_at' => $this->created_at,
        ];
    }
}
