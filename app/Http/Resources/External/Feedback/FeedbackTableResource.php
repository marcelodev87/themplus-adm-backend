<?php

namespace App\Http\Resources\External\Feedback;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackTableResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'user_name' => $this->externalUser->name,
            'organization_name' => $this->externalEnterprise->name,
            'created' => $this->created_at->format('d-m-Y'),
            'message' => $this->message,
        ];
    }
}
