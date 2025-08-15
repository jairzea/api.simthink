<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyntheticResponseUpdateRequest extends FormRequest
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
            'synthetic_user_id' => ['required'],
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
        ];
    }
}
