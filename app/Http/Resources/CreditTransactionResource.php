<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'amount_usd' => $this->amount_usd,
            'credits_added' => $this->credits_added,
            'package_type' => $this->package_type,
            'payment_method' => $this->payment_method,
            'invoice_number' => $this->invoice_number,
            'status' => $this->status,
            'metadata' => $this->metadata,
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
