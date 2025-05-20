<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\ImageGalleryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FoodResource extends JsonResource
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
            'category' => new CategoryResource($this->whenLoaded('category')),
            'chef' => new ChefResource($this->whenLoaded('chef')),
            'images' => ImageGalleryResource::collection($this->whenLoaded('imageGalleries')),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'offer_price' => $this->offer_price,
            'status' => $this->status,
            'preparation_time' => $this->preparation_time,
            'rating' => $this->rating,
            'food_type' => $this->food_type,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,


        ];
    }
}