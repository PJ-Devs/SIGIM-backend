<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            'description' => 'string|max:255',
            'added_stock' => 'numeric|min:1',
            'decreased_stock' => 'numeric|min:1',
            'sale_price' => 'decimal|min:0',
            'supplier_price' => 'decimal|min:0',
            'minimal_safe_stock' => 'numeric|min:1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->sale_price < $this->supplier_price) {
                $validator->errors()->add('sale_price', 'Sale price must be greater than or equal to supplier price');
            } else if ($this->added_stock && $this->decreased_stock) {
                $validator->errors()->add('added_stock', 'You can only add or decrease stock');
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
            'stock.required' => 'Stock is required',
            'stock.numeric' => 'Stock must be a number',
            'stock.min' => 'Stock must be greater than or equal to 1',
            'sale_price.required' => 'Sale price is required',
            'sale_price.decimal' => 'Sale price must be a number',
            'sale_price.min' => 'Sale price must be greater than or equal to 0',
            'supplier_price.required' => 'Supplier price is required',
            'supplier_price.decimal' => 'Supplier price must be a number',
            'supplier_price.min' => 'Supplier price must be greater than or equal to 0',
            'minimal_safe_stock.required' => 'Minimal safe stock is required',
            'minimal_safe_stock.numeric' => 'Minimal safe stock must be a number',
            'minimal_safe_stock.min' => 'Minimal safe stock must be greater than or equal to 1',
            'thumbnail.image' => 'Thumbnail must be an image',
            'thumbnail.mimes' => 'Thumbnail must be a file of type: jpeg, png, jpg',
            'thumbnail.max' => 'Thumbnail may not be greater than 2048 kilobytes',
        ];
    }
}
