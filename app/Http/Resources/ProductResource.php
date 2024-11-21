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
            'sale_price' => $this->sale_price,
            'supplier_price' => $this->supplier_price,
            'stock' => $this->stock,
            'minimal_safe_stock' => $this->minimal_safe_stock,
            'category' => new CategoryResource($this->category),
            'discount' => $this->discount,
            'thumbnail' => $this->thumbnail,
        ];
    }
}
