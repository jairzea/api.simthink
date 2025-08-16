<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrepareStudyResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => $this['status'],
            'temp_id' => $this['temp_id'],
            'investigation_id' => $this['investigation_id'],
            'improved' => [
                'study_id' => $this['improved']['study_id'] ?? null,
                'company_name' => $this['improved']['company_name'] ?? null,
                'company_website' => $this['improved']['company_website'] ?? null,
                'company_raw_description' => $this['improved']['company_raw_description'] ?? null,
                'product_service_text' => $this['improved']['product_service_text'] ?? null,
                'product_image_paths' => $this['improved']['product_image_paths'] ?? [],
                'document_paths' => $this['improved']['document_paths'] ?? [],
                'hypotheses' => $this['improved']['hypotheses'] ?? [],
                'customer_profile' => $this['improved']['customer_profile'] ?? null,
                'agent1_output' => $this['improved']['agent1_output'] ?? null,
                'product_analysis_summary' => $this['improved']['product_analysis_summary'] ?? null,
                'refined_hypotheses' => $this['improved']['refined_hypotheses'] ?? [],
                'refined_customer_profile' => $this['improved']['refined_customer_profile'] ?? null,
                'synthetic_users_list' => $this['improved']['synthetic_users_list'] ?? [],
                'synthetic_user_count' => $this['improved']['synthetic_user_count'] ?? 0,
            ],
        ];
    }
}