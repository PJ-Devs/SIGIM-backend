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
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|numeric|min:0',
            'sale_price' => 'required|decimal|min:0',
            'supplier_price' => 'required|decimal|min:0',
            'minimal_safe_stock' => 'required|numeric|min:1',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->stock < $this->minimal_safe_stock) {
                $validator->errors()->add('stock', 'Stock must be greater than or equal to minimal safe stock');
            }
            if ($this->sale_price < $this->supplier_price) {
                $validator->errors()->add('sale_price', 'Sale price must be greater than or equal to supplier price');
            }
        });
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'description.required' => 'Description is required',
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Category not found',
            'stock.required' => 'Stock is required',
            'stock.numeric' => 'Stock must be a number',
            'stock.min' => 'Stock must be at least 0',
            'sale_price.required' => 'Sale price is required',
            'sale_price.decimal' => 'Sale price must be a decimal',
            'sale_price.min' => 'Sale price must be at least 0',
            'supplier_price.required' => 'Supplier price is required',
            'supplier_price.decimal' => 'Supplier price must be a decimal',
            'supplier_price.min' => 'Supplier price must be at least 0',
            'minimal_safe_stock.required' => 'Minimal safe stock is required',
            'minimal_safe_stock.numeric' => 'Minimal safe stock must be a number',
            'minimal_safe_stock.min' => 'Minimal safe stock must be at least 1',
        ];
    }
}
