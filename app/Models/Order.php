<?php

namespace App\Models;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'total_price', 'address_id', 'paid'];


    // public function orderItems()
    // {
    //     return $this->hasMany(OrderItem::class);
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
