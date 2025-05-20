<?php

namespace App\Models;

use App\Models\Chef;
use App\Models\Review;
use App\Models\Category;
use App\Models\Follower;
use App\Models\ImageGallery;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $fillable = ['category_id', 'chef_id', 'name', 'description', 'price', 'offer_price', 'preparation_time', 'rating', 'food_type', 'status'];

    protected $table = 'foods';
    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship with Chef
    public function chef()
    {
        return $this->belongsTo(Chef::class);
    }

    public function followers()
    {
        return $this->hasMany(Follower::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('star') ?? 0;
    }
    public function imageGalleries()
    {
        return $this->hasMany(ImageGallery::class);
    }

}