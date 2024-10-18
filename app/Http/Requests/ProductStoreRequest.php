<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return True;
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
            'description' => 'nullable|string|max:100',
            'status' => 'required|in:deleted,unavailable,available',
            'stock' => 'required|integer|min:0',
            'supplier_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|string|max:100',
            'barcode' => 'nullable|string|max:100',
            'minimal_safe_stock' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'enterprise_id' => 'required|ulid|exists:enterprises,id',
            'category_id' => 'required|integer|exists:categories,id',
            'supplier_id' => 'required|integer|exists:suppliers,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The product name is required.',
            'name.max' => 'The product name may not be greater than 100 characters.',
            'status.required' => 'The product status is required.',
            'status.in' => 'The selected status is invalid.',
            'stock.required' => 'The stock quantity is required.',
            'stock.integer' => 'The stock must be an integer.',
            'supplier_price.required' => 'The supplier price is required.',
            'supplier_price.numeric' => 'The supplier price must be a number.',
            'sale_price.required' => 'The sale price is required.',
            'sale_price.numeric' => 'The sale price must be a number.',
            'minimal_safe_stock.required' => 'The minimal safe stock is required.',
            'minimal_safe_stock.integer' => 'The minimal safe stock must be an integer.',
            'discount.numeric' => 'The discount must be a number.',
            'discount.max' => 'The discount may not be greater than 100.',
            'enterprise_id.required' => 'The enterprise ID is required.',
            'enterprise_id.exists' => 'The selected enterprise does not exist.',
            'category_id.required' => 'The category ID is required.',
            'category_id.exists' => 'The selected category does not exist.',
            'supplier_id.required' => 'The supplier ID is required.',
            'supplier_id.exists' => 'The selected supplier does not exist.',
        ];
    }
}
