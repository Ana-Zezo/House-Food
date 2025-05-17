<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'city',
        'center',
        'neighborhood',
        'street',
        'building_number',
    ];

    // 🔹 العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}