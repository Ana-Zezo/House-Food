<?php

namespace App\Http\Controllers\Api;

use App\Models\Food;
use App\Models\Order;
use App\Models\Review;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:foods,id',
            'star' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $user = Auth::user();

        $hasOrderedFood = \App\Models\OrderItem::whereHas('order', function ($q) use ($user) {
            $q->where('user_id', $user->id)->where('paid', 'success');
        })
            ->where('food_id', $request->food_id)
            ->exists();

        if (!$hasOrderedFood) {
            return ApiResponse::sendResponse(false, 'You can only review foods you have ordered.', 403);
        }

        $existingReview = Review::where('user_id', $user->id)
            ->where('food_id', $request->food_id)
            ->first();

        if ($existingReview) {
            return ApiResponse::sendResponse(false, 'You have already reviewed this food.', 403);
        }

        $review = Review::create([
            'user_id' => $user->id,
            'food_id' => $request->food_id,
            'star' => $request->star,
            'comment' => $request->comment,
        ]);

        $food = Food::find($request->food_id);
        $food->rating = $food->reviews()->avg('star') ?? 0;
        $food->save();

        return ApiResponse::sendResponse(true, 'Review added successfully.', $review);
    }


}