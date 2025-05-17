<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('wallet/callback', [WalletController::class, 'walletRechargeSuccess'])->name('payment.wallet.callback');
Route::get('wallet/error', [WalletController::class, 'rechargeWalletError'])->name('payment.wallet.error');
Route::get('callback', [OrderController::class, 'paymentCallback'])->name('payments.callback');
Route::get('error', [OrderController::class, 'paymentCallback'])->name('payments.error');