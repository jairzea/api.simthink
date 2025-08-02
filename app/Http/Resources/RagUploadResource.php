<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RagUploadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'investigation_id' => $this->investigation_id,
            'filename' => $this->filename,
            'size_kb' => $this->size_kb,
            'file_type' => $this->file_type,
            'path' => $this->path,
            'status' => $this->status,
            'user_investigation_id' => $this->user_investigation_id,
        ];
    }
}
