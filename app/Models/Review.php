<?php

namespace App\Models;

use App\Models\Food;
use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['food_id', 'user_id', 'star', 'comment'];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}