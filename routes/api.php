<?php

use Illuminate\Http\Request;
use App\Http\Middleware\EnsureUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthUserController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\FollowerController;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Api\UserHomeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthUserController::class, 'register']);
Route::post('/login', [AuthUserController::class, 'login']);
Route::post('/verify-otp', [AuthUserController::class, 'verifyOTP'])->name('verify-otp');
Route::post('/logout', [AuthUserController::class, 'logout'])->middleware('auth:user');
Route::post('/forget-password', [AuthUserController::class, 'forgetPassword'])->name('forget-password');
Route::post('/reset-password', [AuthUserController::class, 'changePassword']);
Route::post('/resend-code', [AuthUserController::class, 'resetCode']);
// -----------------------Auth----------------------------

Route::middleware(EnsureUser::class)->group(function () {
    // -----------------------Home----------------------------
    Route::get('/user/home', [UserHomeController::class, 'index']);
    Route::get('/user/categories', [UserHomeController::class, 'category']);
    Route::get('/user/foods', [UserHomeController::class, 'food']);
    Route::get('/user/offer-prices', [UserHomeController::class, 'offerFood']);
    Route::get('/user/categories/{category}', [UserHomeController::class, 'filterCategory']);

    // -----------------------Profile----------------------------
    Route::get('/profile', [AuthUserController::class, 'profile']);
    Route::post('/profile', [AuthUserController::class, 'updateProfile']);

    Route::get('/foods/{food}', [FoodController::class, 'show']);

    // -----------------------Cart----------------------------
    Route::post('addCart', [CartController::class, 'addToCart']);
    Route::put('/cart', [CartController::class, 'updateCart']);
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::delete('/cart/{id}', [CartController::class, 'removeFromCart']);



    // -----------------------Order----------------------------
    Route::get('/checkout', [OrderController::class, 'checkout']);
    Route::post('/pay-order/{orderId}', [OrderController::class, 'payOrder']);
    Route::get('/cancel-order/{orderItemId}', [OrderController::class, 'cancelOrder']);
    Route::get('/my-orders', [OrderController::class, 'myOrders']);



    // -----------------------Follower----------------------------
    Route::post('/follow-chef', [FollowerController::class, 'followChef']);
    Route::delete('/unfollow-chef/{chef_id}', [FollowerController::class, 'unfollowChef']);
    Route::get('/my-followed-chefs', [FollowerController::class, 'myFollowedChefs']);
    Route::get('/followed/{chef_id}', [FollowerController::class, 'getFollowedChefDetails']);


    // -----------------------Review----------------------------
    Route::post('/reviews', [ReviewController::class, 'store']);

    // -----------------------Wallet----------------------------

    Route::post('/wallet/recharge', [WalletController::class, 'rechargeWallet']);
    Route::get('/wallet/balance', [WalletController::class, 'getAllWallets']);
    // Route::get('/wallet/balance', [WalletController::class, 'getWalletBalance']);



    // -----------------------Addresses----------------------------
    Route::apiResource('addresses', AddressController::class);
});

Route::get('walletRechargeSuccess', [WalletController::class, 'walletRechargeSuccess'])->name('payment.wallet.callback');
Route::get('walletRechargeError', [WalletController::class, 'rechargeWalletError'])->name('payment.wallet.error');
Route::apiResource('categories', CategoryController::class);