<?php

namespace App\Models;

use App\Models\Chef;
use App\Models\Food;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'food_id', 'chef_id', 'qty', 'subtotal', 'chef_status', 'chef_status_updated_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function chef()
    {
        return $this->belongsTo(Chef::class);
    }
}