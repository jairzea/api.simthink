<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestigationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'status' => $this->status,
            'sample_size' => $this->sample_size,
            'type' => $this->type,
            'use_rag' => $this->use_rag,
            'cost_credits' => $this->cost_credits,
            'result_summary' => $this->result_summary,
            'completed_at' => $this->completed_at,
            'user' => UserResource::make($this->whenLoaded('user')),
            'syntheticUsers' => SyntheticUserCollection::make($this->whenLoaded('syntheticUsers')),
            'ragUpload' => RagUploadResource::make($this->whenLoaded('ragUpload')),
        ];
    }
}
