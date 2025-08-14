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
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'original_price' => $this->original_price,
            'in_stock' => $this->in_stock,
            'category' => $this->category->name,
            'rating' => $this->reviews->avg('rating'),
            'review_count' => $this->reviews->count(),
            'image_url' => $this->image_url,
        ];
}
}
