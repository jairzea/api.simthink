<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestigationFolderResource extends JsonResource
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
            'user' => UserResource::make($this->whenLoaded('user')),
            'investigationFolderItems' => InvestigationFolderItemCollection::make($this->whenLoaded('investigationFolderItems')),
        ];
    }
}
