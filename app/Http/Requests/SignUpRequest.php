<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
            'enterprise_name' => 'required|string',
            'enterprise_NIT' => 'required|string|unique:enterprises,NIT|max:50',
            'enterprise_email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|unique:enterprises,phone_number',

            'owner_name' => 'required|string',
            'owner_email' => 'required|email|unique:users,email',
            'owner_password' => 'required|string|min:8',

            'device_name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'enterprise_name.required' => 'The enterprise name field is required.',
            'enterprise_name.string' => 'The enterprise name field must be a string.',
            'enterprise_NIT.required' => 'The enterprise NIT field is required.',
            'enterprise_NIT.string' => 'The enterprise NIT field must be a string.',
            'enterprise_NIT.unique' => 'The enterprise NIT field must be unique.',
            'enterprise_NIT.max' => 'The enterprise NIT field must be at most 50 characters.',
            'enterprise_email.required' => 'The enterprise email field is required.',
            'enterprise_email.email' => 'The enterprise email field must be a valid email address.',
            'enterprise_email.unique' => 'The enterprise email field must be unique.',
            'phone_number.required' => 'The phone number field is required.',
            'phone_number.string' => 'The phone number field must be a string.',
            'phone_number.unique' => 'The phone number field must be unique.',

            'owner_name.required' => 'The owner name field is required.',
            'owner_name.string' => 'The owner name field must be a string.',
            'owner_email.required' => 'The owner email field is required.',
            'owner_email.email' => 'The owner email field must be a valid email address.',
            'owner_email.unique' => 'The owner email field must be unique.',
            'owner_password.required' => 'The owner password field is required.',
            'owner_password.string' => 'The owner password field must be a string.',
            'owner_password.min' => 'The owner password field must be at least 8 characters.',

            'device_name.required' => 'The device name field is required.'
        ];
    }
}
