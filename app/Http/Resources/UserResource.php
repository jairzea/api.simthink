<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'company' => $this->company,
            'phone' => $this->phone,
            'email_verified_at' => $this->email_verified_at,
            'credits' => $this->credits,
            'storage_used_mb' => $this->storage_used_mb,
            'storage_limit_mb' => $this->storage_limit_mb,
            'roles' => RoleResource::make($this->whenLoaded('roles')),
        ];
    }
}