<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Food;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'food_id' => 'required|exists:foods,id',
            'qty' => 'required|integer|min:1',
        ]);

        $food = Food::findOrFail($request->food_id);

        $cartItem = Cart::where('user_id', $user->id)
            ->where('food_id', $food->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('qty', $request->qty);
        } else {
            Cart::create([
                'user_id' => $user->id,
                'food_id' => $food->id,
                'qty' => $request->qty,
            ]);
        }
        return ApiResponse::sendResponse(true, __('messages.added_to_cart_successfully'));
    }
    public function getCart()
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('food')->get();
        return ApiResponse::sendResponse(true, __('messages.cart_retrieved_successfully'), $cartItems);
    }



    public function removeFromCart($id)
    {
        $user = Auth::user();
        $cartItem = $user->cartItems()->where('id', $id)->first();

        if (!$cartItem) {
            return ApiResponse::errorResponse(false, "Item Not Exists in Cart.");
        }

        $cartItem->delete();

        return ApiResponse::sendResponse(true, __('messages.removed_from_cart_successfully'));
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:carts,id',
            'qty' => 'required|array',
            'qty.*' => 'integer|min:1',
        ]);

        foreach ($request->ids as $index => $cartId) {
            $cartItem = Cart::find($cartId);
            if ($cartItem) {
                $cartItem->qty = $request->qty[$index];
                $cartItem->save();
            }
        }

        return ApiResponse::sendResponse(true, __('messages.cart_updated_successfully'));
    }


}