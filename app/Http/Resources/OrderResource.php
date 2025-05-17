<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'orderNumber' => $this->orderNumber,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'items' => $this->items->map(function ($item) {
                return [
                    'food_name' => $item->food->name,
                    'qty' => $item->qty,
                    'subtotal' => $item->subtotal,
                    'food_type' => $item->food_type,
                    'chef_status' => $item->chef_status, // 🔹 حالة الطلب عند الطاهي
                ];
            }),
            'transaction' => [
                'status' => optional($this->transactions->first())->status, // 🔹 حالة الدفع
                'amount' => optional($this->transactions->first())->amount,
            ],
        ];
    }
}