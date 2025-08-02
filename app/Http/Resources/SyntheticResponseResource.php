<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SyntheticResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'synthetic_user_id' => $this->synthetic_user_id,
            'question' => $this->question,
            'answer' => $this->answer,
            'syntheticUser' => SyntheticUserResource::make($this->whenLoaded('syntheticUser')),
        ];
    }
}
