<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RagUploadStoreRequest extends FormRequest
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
            'user_id' => ['string'],
            'investigation_id' => ['nullable'],
            'filename' => ['string'],
            'size_kb' => ['integer'],
            'file_type' => ['in:pdf,doc,docx,txt,xlsx,image'],
            'path' => ['string'],
            'status' => ['in:uploaded,processed,deleted'],
            'files' => ['required','array','min:1'],
            'files.*' => ['file','max:10240', 'mimes:pdf,doc,docx,txt,jpg,jpeg,png'],
        ];
    }
}