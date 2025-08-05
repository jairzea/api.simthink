<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvestigationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'            => ['required', 'string'],
            'type'            => ['required', 'in:insight,imss,other'],
            'use_rag'         => ['required', 'boolean'],
            'status'          => ['required', 'in:created,processing,completed,failed'],
            'sample_size'     => ['required', 'integer', 'min:1'],
            'cost_credits'    => ['required', 'integer', 'min:0'],
            'result_summary'  => ['nullable', 'string'],
            'completed_at'    => ['nullable', 'date'],
            'context_info'    => ['required', 'string'],
            'target_persona'  => ['required', 'string'],
            'research_goal'   => ['required', 'string'],
            'product_info'    => ['required', 'string'],

        ];
    }
}