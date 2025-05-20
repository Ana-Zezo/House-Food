<?php

use Illuminate\Http\Request;
use App\Http\Middleware\EnsureChef;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ChefAuthController;
use App\Http\Controllers\Api\WithdrawController;


Route::post('/register', [ChefAuthController::class, 'register']);
Route::post('/login', [ChefAuthController::class, 'login']);
Route::post('/verify-otp', [ChefAuthController::class, 'verifyOTP'])->name('verify-otp');
Route::post('/logout', [ChefAuthController::class, 'logout'])->middleware('auth:chef');
Route::post('/forget-password', [ChefAuthController::class, 'forgetPassword'])->name('forget-password');
Route::post('/reset-password', [ChefAuthController::class, 'changePassword']);
Route::post('/resend-code', [ChefAuthController::class, 'resetCode']);
// -----------------------Auth----------------------------

Route::middleware(EnsureChef::class)->group(function () {
    Route::get('/profile', [ChefAuthController::class, 'profile']);
    Route::post('/profile', [ChefAuthController::class, 'updateProfile']);
    // -----------------------Foods----------------------------
    Route::apiResource('foods', FoodController::class);
    Route::put('update/order/{id}', [OrderController::class, 'updateOrdersByStatus']);
    Route::get('home', [OrderController::class, 'getOrdersByStatus']);


    Route::get('/withdraws', [WithdrawController::class, 'index']);
    Route::post('/withdraws', [WithdrawController::class, 'store']);
    // ----------------Withdraw-------------------------

});