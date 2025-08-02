<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvestigationFolderItemUpdateRequest extends FormRequest
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
            'folder_id' => ['required'],
            'investigation_id' => ['required'],
            'investigation_folder_investigation_id' => ['required', 'integer', 'exists:investigation_folder_investigations,id'],
        ];
    }
}
