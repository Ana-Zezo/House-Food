<?php

namespace App\Models;

use App\Models\Food;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'image', 'status'];


    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}