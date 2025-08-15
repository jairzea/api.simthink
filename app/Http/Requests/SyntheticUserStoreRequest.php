<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyntheticUserStoreRequest extends FormRequest
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
            'investigation_id' => ['required'],
            'code' => ['required', 'string', 'unique:synthetic_users,code'],
            'ocean_profile' => ['required', 'json'],
            'metadata' => ['nullable', 'json'],
        ];
    }
}
