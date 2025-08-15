<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RagUploadUpdateRequest extends FormRequest
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
            'user_id' => ['required'],
            'investigation_id' => ['nullable'],
            'filename' => ['required', 'string'],
            'size_kb' => ['required', 'integer'],
            'file_type' => ['required', 'in:pdf,doc,docx,txt,xlsx,image'],
            'path' => ['required', 'string'],
            'status' => ['required', 'in:uploaded,processed,deleted'],
        ];
    }
}