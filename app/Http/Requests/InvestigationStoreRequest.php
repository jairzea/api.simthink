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
            'name' => ['required', 'string'],
            'sample_size' => ['required', 'integer'],
            'type' => ['required', 'in:insight,imss,other'],
            'use_rag' => ['required'],
            'user_id' => ['required'],
            'name' => ['required', 'string'],
            'status' => ['required', 'in:created,processing,completed,failed'],
            'sample_size' => ['required', 'integer'],
            'type' => ['required', 'in:insight,imss,other'],
            'use_rag' => ['required'],
            'cost_credits' => ['required', 'integer'],
            'result_summary' => ['nullable', 'string'],
            'completed_at' => ['nullable'],
            'user_id' => ['required'],
        ];
    }
}
