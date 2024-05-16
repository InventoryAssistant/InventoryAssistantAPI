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
            'content' => $this->content,
            'unit' => $this->unit?->abbreviation,
            'unit_id' => $this->unit?->id,
            'barcode' => $this->barcode,
            'category' => $this->category->name,
            'category_id' => $this->category->id,
            'locations' => $this->location_products
        ];
    }
}
