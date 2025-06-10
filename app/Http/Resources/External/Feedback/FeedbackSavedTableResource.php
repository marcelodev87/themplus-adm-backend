<?php

namespace App\Http\Resources\External\Feedback;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackSavedTableResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'user_name' => $this->user_name,
            'organization_name' => $this->enterprise_name,
            'created' => $this->date_feedback->format('d-m-Y'),
            'message' => $this->message,
        ];
    }
}
