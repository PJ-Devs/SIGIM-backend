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
            'category' => new CategoryResource($this->category),
            'thumbnail' => $this->thumbnail,
        ];
    }
}
