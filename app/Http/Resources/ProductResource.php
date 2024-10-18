<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'stock' => $this->stock,
            'supplier_price' => $this->supplier_price,
            'sale_price' => $this->sale_price,
            'thumbnail' => $this->thumbnail,
            'barcode' => $this->barcode,
            'minimal_safe_stock' => $this->minimal_safe_stock,
            'discount' => $this->discount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
