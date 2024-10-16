<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnterpriseUpdateRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone_number' => 'nullable|string|max:100',
            'currency' => 'nullable|string|max10|in:COP,USD',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name is required.',
            'email.email' => 'The email must be a valid address.',
            'currency.in' => 'The selected currency is not valid.',
        ];
    }
}
