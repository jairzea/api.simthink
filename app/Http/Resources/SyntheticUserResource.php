<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SyntheticUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'investigation_id' => $this->investigation_id,
            'code' => $this->code,
            'ocean_profile' => $this->ocean_profile,
            'metadata' => $this->metadata,
            'investigation' => InvestigationResource::make($this->whenLoaded('investigation')),
            'syntheticResponses' => SyntheticResponseResource::make($this->whenLoaded('syntheticResponses')),
        ];
    }
}