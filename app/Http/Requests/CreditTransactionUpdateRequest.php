<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditTransactionUpdateRequest extends FormRequest
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
            'amount_usd' => ['required', 'numeric', 'between:-99999999.99,99999999.99'],
            'payment_method' => ['required', 'string'],
            'user_id' => ['required'],
            'amount_usd' => ['required', 'numeric', 'between:-99999999.99,99999999.99'],
            'credits_added' => ['required', 'integer'],
            'package_type' => ['required', 'in:basic,premium,pro,enterprise'],
            'payment_method' => ['required', 'string'],
            'invoice_number' => ['required', 'string'],
            'status' => ['required', 'in:pending,completed,failed'],
            'metadata' => ['nullable', 'json'],
            'user_id' => ['required'],
            'amount_usd' => ['required', 'numeric', 'between:-99999999.99,99999999.99'],
            'credits_added' => ['required', 'integer'],
            'package_type' => ['required', 'in:basic,premium,pro,enterprise'],
            'payment_method' => ['required', 'string'],
            'invoice_number' => ['required', 'string'],
            'status' => ['required', 'in:pending,completed,failed'],
            'metadata' => ['nullable', 'json'],
        ];
    }
}
