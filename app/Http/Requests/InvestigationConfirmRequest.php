<?php

// app/Http/Requests/InvestigationConfirmRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvestigationConfirmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}