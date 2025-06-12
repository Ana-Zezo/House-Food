<?php

namespace App\Models;

use App\Models\Chef;
use App\Models\Review;
use App\Models\Category;
use App\Models\Follower;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Food extends Model

{
    use HasFactory;

    protected $fillable = ['category_id', 'chef_id', 'name', 'description', 'price', 'offer_price', 'preparation_time', 'rating', 'food_type', 'image', 'status'];

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
}
