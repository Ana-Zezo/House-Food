<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'status'
    ];

    // 🔹 العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}