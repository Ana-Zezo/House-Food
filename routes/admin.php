<?php

use App\Http\Controllers\Api\AuthUserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\WithdrawController;
use App\Http\Middleware\EnsureUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::put('withdraws/{withdraw}', [WithdrawController::class, 'updateWithdrawStatus']);
Route::get('withdraws', [WithdrawController::class, 'AllWithdraws']);

Route::middleware(EnsureUser::class)->group(function () {
    Route::get('/profile', [AuthUserController::class, 'profile']);
    Route::post('/profile', [AuthUserController::class, 'updateProfile']);
    // -----------------------Products----------------------------
});