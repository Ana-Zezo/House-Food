<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'paymentId',
        'amount',
        'status'
    ];

    // 🔹 العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔹 العلاقة مع الطلب (إن وجد)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}