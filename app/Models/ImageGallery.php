<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageGallery extends Model
{
    protected $fillable = [
        'food_id',
        'image',
    ];
}