<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChefResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'countSubscribe' => $this->countSubscribe,
            'wallet' => $this->wallet,
            'bio' => $this->bio,
            'totalOrder' => $this->totalOrder,
            'otp' => $this->otp,
            'image' => $this->image,
            'token' => $this->token,
        ];
    }
}