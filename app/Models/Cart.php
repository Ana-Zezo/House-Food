<?php

namespace App\Models;

use App\Models\Food;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'food_id', 'qty'];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}