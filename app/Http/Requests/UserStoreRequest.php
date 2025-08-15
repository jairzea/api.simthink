<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => 'required|string|min:8',
            'company' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'email_verified_at' => ['nullable'],
            'credits' => ['nullable', 'integer'],
            'storage_used_mb' => ['nullable', 'integer'],
            'storage_limit_mb' => ['nullable', 'integer'],
            'remember_token' => ['nullable', 'string'],
            'email_notifications' => ['nullable', 'boolean'],
            'product_updates' => ['nullable', 'boolean'],

        ];
    }
}