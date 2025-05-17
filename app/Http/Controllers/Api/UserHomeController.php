<?php

namespace App\Http\Controllers\Api;

use App\Models\Food;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
use App\Trait\ApiResponse;

class UserHomeController extends Controller
{
    public function index(Request $request)
    {
        $foods = Food::with(['chef:id,name,image', 'category:id,name,image', 'reviews'])
            ->when($request->category_id, function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->when($request->food_name, function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->food_name}%");
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($food) {
                return [
                    'id' => $food->id,
                    'name' => $food->name,
                    // 'image' => $food->image,
                    'image' => "storage/" . $food->image,
                    'rate' => $food->rating,
                    'food_type' => $food->food_type,
                    'preparation_time' => $food->preparation_time,
                    'price_before' => $food->price,
                    'price_after' => $food->offer_price ?? $food->price,
                    'review_count' => $food->reviews->count(), 
                    'category' => [
                        'id' => $food->category->id,
                        'name' => $food->category->name,
                        // 'image' => $food->category->image,
                        'image' => $food->category->image,
                    ],
                    'chef' => [
                        'id' => $food->chef->id,
                        'name' => $food->chef->name,
                        // 'image' => $food->chef->image,
                        'image' => $food->chef->image,
                    ],
                ];
            });

        $categories = Category::select('id', 'name', 'image')->get();
        $offerFoods = Food::whereNotNull('offer_price')
            ->select('id', 'name', 'image', 'price', 'offer_price')
            ->orderByDesc('created_at')
            ->get();

        return ApiResponse::sendResponse(true, 'Data Retrieve successful', [
            'categories' => $categories,
            'foods' => $foods,
            'offerFoods' => $offerFoods,
        ]);
    }
    public function offerFood()
    {
        $offerFoods = Food::whereNotNull('offer_price')
            ->select('id', 'name', 'image', 'price', 'offer_price')
            ->orderByDesc('created_at')
            ->get();

        return ApiResponse::sendResponse(true, 'Data Retrieve successful', $offerFoods);
    }
    public function category()
    {
        $categories = Category::select('id', 'name', 'image')->get();

        ApiResponse::sendResponse(true, 'Data Retrieve successful', $categories);
    }
    public function food(Request $request)
    {
        $foods = Food::with(['chef:id,name,image', 'category:id,name,image', 'reviews'])
            ->when($request->category_id, function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->when($request->food_name, function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->food_name}%");
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($food) {
                return [
                    'id' => $food->id,
                    'name' => $food->name,
                    'image' => $food->image,
                    'rate' => $food->rating,
                    'food_type' => $food->food_type,
                    'preparation_time' => $food->preparation_time,
                    'price_before' => $food->price,
                    'price_after' => $food->offer_price ?? $food->price,
                    'review_count' => $food->reviews->count(), 
                    'category' => [
                        'id' => $food->category->id,
                        'name' => $food->category->name,
                        // 'image' => $food->category->image,
                        'image' => $food->category->image,
                    ],
                    'chef' => [
                        'id' => $food->chef->id,
                        'name' => $food->chef->name,
                        'image' => $food->chef->image,
                    ],
                ];
            });

        ApiResponse::sendResponse(true, 'Data Retrieve successful', $foods);
    }

    public function filterCategory(Category $category)
    {
        $foods = Food::where('category_id', $category->id)->get();
        return ApiResponse::sendResponse(true, 'Data Retrieve successful', FoodResource::collection($foods));
    }
}