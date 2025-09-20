<?php
namespace App\Services;

use Log;

class AgentResponseNormalizer
{
    /**
     * Normaliza la respuesta del endpoint /prepare del agente.
     *
     * @param array|object $raw
     * @return array
     */
    public function normalizePrepareResponse(array|object $raw): array
    {
        $tempId = data_get($raw, 'temp_id') ?? data_get($raw, 'tempID') ?? null;
        $improved = data_get($raw, 'improved', []);

        // aseguramos array
        if (! is_array($improved)) {
            $improved = json_decode(json_encode($improved), true) ?: [];
        }

        Log::info("normalize", $improved);

        $dto = [
            'studyId' => $improved['study_id'] ?? null,
            'companyRawDescription' =>  $improved['company_name'] ?? $improved['company_raw_description'] ?? null,
            'customerProfile' => $improved['refined_customer_profile'] ?? null,
            'hypotheses' => $improved['refined_hypotheses'] ?? null,
            'productSummary' => $improved['product_analysis_summary'] ?? null,
            'tempId' => $tempId,
            'raw' => is_array($raw) ? $raw : json_decode(json_encode($raw), true) ?? []
        ];

        Log::info("dto", $dto);


        return $dto;
    }
}