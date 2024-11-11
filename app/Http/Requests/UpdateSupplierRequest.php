<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
            'name' => 'string|max:60|min:3',
            'email' => 'email|unique:suppliers,email,' . $this->supplier,
            'phone' => 'string|max:15|min:10',
            'address' => 'string|max:255|min:5',
        ];
    }

    public function messages():array{
        return[
            'name.string' => 'Name must be a string',
            'name.max' => 'Name must not be greater than 60 characters',
            'name.min' => 'Name must be at least 3 characters',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email is already taken',
            'phone.string' => 'Phone must be a string',
            'phone.max' => 'Phone must not be greater than 15 characters',
            'phone.min' => 'Phone must be at least 10 characters',
            'address.string' => 'Address must be a string',
            'address.max' => 'Address must not be greater than 255 characters',
            'address.min' => 'Address must be at least 5 characters',
        ];
    }
}
