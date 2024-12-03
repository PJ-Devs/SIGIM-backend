<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCollaboratorsRequest extends FormRequest
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
            'colaborators.*.name' => 'required|string',
            'colaborators.*.email' => 'required|email|unique:users,email',
            'colaborators.*.role' => 'required|integer|exists:roles,id|not_in:1',
        ];
    }

    public function messages()
    {
        return [
            'colaborators.array' => 'The colaborators field must be an array.',
            'colaborators.*.name.required' => 'The name field is required.',
            'colaborators.*.name.string' => 'The name field must be a string.',
            'colaborators.*.email.required' => 'The email field is required.',
            'colaborators.*.email.email' => 'The email field must be a valid email address.',
            'colaborators.*.email.unique' => 'The email field must be unique.',
            'colaborators.*.role.required' => 'The role field is required.',
            'colaborators.*.role.number' => 'The role field must be a number.',
            'colaborators.*.role.exists' => 'The selected role is invalid.',
            'colaborators.*.role.not_in' => 'The selected role is invalid.',

        ];
    }
}
