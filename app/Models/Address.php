<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;
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
