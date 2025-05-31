<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Models\Product $this */
        return [
            'id'           => $this->id,
            'name'         => $this->title,
            'description'  => $this->description,
            'price'        => $this->price !== null ? (float) $this->price : null,
            'image'        => $this->images->first()->url ?? null,
            'category'     => $this->category->name ?? null,
            'availability' => true,
            'rating'       => null,
            'reviewCount'  => null,
            'createdAt'    => optional($this->created_at)->toIso8601String(),
            'updatedAt'    => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
