<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckPendingOrders implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }


    public function handle()
    {
        Log::info("Job ran at " . now());

        $expiredTime = now()->subHours(24);

        $expiredOrderItems = OrderItem::whereHas('order', function ($query) {
            $query->where('paid', 'success');
        })
            ->where('chef_status', 'pending')
            ->where('chef_status_updated_at', '<', $expiredTime)
            ->get();

        foreach ($expiredOrderItems as $orderItem) {
            $orderItem->update([
                'chef_status' => 'reject',
                'chef_status_updated_at' => now(),
            ]);
        }

        $orders = Order::where('paid', 'success')
            ->whereHas('orderItems', function ($query) {
                $query->where('chef_status', 'pending');
            })
            ->get();

        foreach ($orders as $order) {
            $orderItems = $order->orderItems;

            if ($orderItems->where('chef_status', 'pending')->count() === 0) {
                if ($orderItems->where('chef_status', 'reject')->count() > 0) {
                    $order->update(['status' => 'reject']);
                } elseif ($orderItems->where('chef_status', 'accept')->count() > 0) {
                    $order->update(['status' => 'accept']);
                }
            }
        }
    }
}