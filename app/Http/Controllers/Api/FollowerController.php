<?php

namespace App\Http\Controllers\Api;

use App\Models\Chef;
use App\Models\Food;
use App\Models\User;
use App\Models\Follower;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Trait\ApiResponse;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{
    public function followChef(Request $request)
    {
        $request->validate([
            'chef_id' => 'required|exists:chefs,id',
        ]);

        $user = Auth::user();

        $exists = Follower::where('user_id', $user->id)
            ->where('chef_id', $request->chef_id)
            ->exists();

        if ($exists) {
            return ApiResponse::errorResponse(false, 'You Are Follow this chef Actually');
        }

        // إضافة المتابعة
        Follower::create([
            'user_id' => $user->id,
            'chef_id' => $request->chef_id,
        ]);

        Chef::where('id', $request->chef_id)->increment('countSubscribe');

        return ApiResponse::sendResponse(true, 'The chef has been successfully followed.');
    }

    public function unfollowChef($chef_id)
    {
        $user = Auth::user();

        $follower = Follower::where('user_id', $user->id)
            ->where('chef_id', $chef_id)
            ->first();

        if (!$follower) {
            return ApiResponse::errorResponse(false, 'You do not follow this chef.');
        }

        $follower->delete();

        return ApiResponse::sendResponse(true, 'The chef has been unfollowed.');
    }

    public function myFollowedChefs()
    {
        $user = Auth::user();
        $chefs = $user->followers()->with('chef')->get()->pluck('chef');

        $foods = Food::whereIn('chef_id', $chefs->pluck('id'))->latest()->get();

        return ApiResponse::sendResponse(true, 'Data Retrieve Successful', [
            'chefs' => $chefs,
            'foods' => $foods
        ]);
    }

    public function getFollowedChefDetails($chef_id)
    {
        $user = Auth::user();

        $followedChefs = Follower::where('user_id', $user->id)->pluck('chef_id');

        if (!$followedChefs->contains($chef_id)) {
            return ApiResponse::errorResponse(false, 'You are not following this chef');
        }


        $chef = Chef::where('id', $chef_id)->first();

        if (!$chef) {

            return ApiResponse::errorResponse(false, 'Chef not found');
        }

        $foods = Food::where('chef_id', $chef_id)->latest()->get();

        return ApiResponse::sendResponse(true, 'Data Retrieve Successful', [
            'chef' => $chef,
            'foods' => $foods
        ]);
    }


}