<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Wallet;
use App\Trait\ApiResponse;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use MyFatoorah\Library\MyFatoorah;
use Illuminate\Support\Facades\Auth;
use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
use App\Http\Resources\Transaction\TransactionResource;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentEmbedded;

class WalletController extends Controller
{
    public $mfConfig = [];
    public function __construct()
    {
        $this->mfConfig = [
            'apiKey' => env('MY_FATOORAH_API_TOKEN'),
            'isTest' => config('myfatoorah.test_mode'),
            'countryCode' => config('myfatoorah.country_iso'),
        ];
    }
    public function rechargeWallet(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $user = Auth::user();
        $amount = $request->input('amount');

        if (!$user->name || !$user->phone) {
            return ApiResponse::errorResponse(false, 'User data is incomplete.');
        }

        $referenceId = 'wallet_recharge_' . uniqid();

        $data = [
            'InvoiceValue' => $amount,
            'CustomerName' => $user->name,
            'NotificationOption' => 'ALL',
            'CustomerEmail' => $user->email ?? 'test@gmail.com',
            'DisplayCurrencyIso' => "EGP",
            'CustomerReference' => $referenceId,
            'MobileCountryCode' => "+20",
            'CustomerMobile' => $user->phone,
            'CallBackUrl' => route('payment.wallet.callback'),
            'ErrorUrl' => route('payment.wallet.error'),
            'Language' => App::getLocale(),
        ];

        try {
            DB::beginTransaction();

            $mfObj = new MyFatoorahPayment($this->mfConfig);
            $paymentResponse = $mfObj->getInvoiceURL($data);

            if (!$paymentResponse || !isset($paymentResponse['invoiceId'])) {
                Log::error('Payment Failed: ' . json_encode($paymentResponse));
                throw new \Exception('Failed to initiate wallet recharge.');
            }

            $paymentId = $paymentResponse['invoiceId'];

            $transaction = Transaction::create([
                'paymentId' => $paymentId,
                'amount' => $amount,
                'order_id' => null,
                'user_id' => $user->id,
                'status' => 'pending',
            ]);

            DB::commit();

            return ApiResponse::sendResponse(true, 'Wallet recharge initiated successfully', [
                'payment_url' => $paymentResponse['invoiceURL'],
                'paymentId' => $paymentId,
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); 
            Log::error('Payment Error: ' . $e->getMessage());

            return ApiResponse::errorResponse(false, 'An error occurred while processing the wallet recharge.');
        }
    }

    public function walletRechargeSuccess(Request $request)
    {
        try {
            $paymentId = $request->input('paymentId');

            if (!$paymentId) {
                return view('payment.failed', ['message' => 'Invalid payment ID.']);
            }

            $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
            $data = $mfObj->getPaymentStatus($paymentId, 'PaymentId');

            if (!isset($data->InvoiceId, $data->CustomerReference, $data->InvoiceStatus)) {
                return view('payment.failed', ['message' => 'Failed to verify payment status.']);
            }

            $transaction = Transaction::where('paymentId', $data->InvoiceId)
                ->where('status', 'pending')
                ->first();

            if (!$transaction) {
                return view('payment.failed', ['message' => 'No pending transaction found for this payment.']);
            }

            $user = User::find($transaction->user_id);
            if (!$user) {
                return view('payment.failed', ['message' => 'User not found.']);
            }

            DB::beginTransaction();

            $wallet = Wallet::create([
                'user_id' => $user->id,
                'amount' => $transaction->amount,
                'type' => 'recharge',
                'status' => 'success',
            ]);

            $transaction->update([
                'status' => 'success',
                'updated_at' => now(),
            ]);

            $user->increment('wallet', $wallet->amount);

            DB::commit();

            return view('payment.success', compact('wallet'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Wallet Recharge Error: ' . $e->getMessage());

            return view('payment.failed', ['message' => 'An error occurred while processing the wallet recharge.']);
        }
    }
    public function rechargeWalletError(Request $request)
    {
        try {
            $paymentId = $request->input('paymentId');

            if (!$paymentId) {
                return ApiResponse::errorResponse(false, 'Invalid payment ID.');
            }

            $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
            $data = $mfObj->getPaymentStatus($paymentId, 'PaymentId');

            if (!isset($data->InvoiceId, $data->InvoiceStatus)) {
                return ApiResponse::errorResponse(false, 'Failed to retrieve payment status.');
            }

            // 🔹 Retrieve pending transaction for this invoice
            $transaction = Transaction::where('paymentId', $data->InvoiceId)
                ->where('status', 'pending')
                ->first();

            if (!$transaction) {
                return ApiResponse::errorResponse(false, 'Wallet record not found.');
            }

            if (in_array($data->InvoiceStatus, ['Failed', 'Canceled'])) {
                $transaction->update([
                    'status' => 'failed',
                    'updated_at' => now(),
                ]);
                return view('payment.failed');
            }

            return ApiResponse::sendResponse(false, 'Payment is still pending.', [
                'status' => $data->InvoiceStatus
            ]);
        } catch (\Exception $e) {
            Log::error('Wallet Recharge Error Callback: ' . $e->getMessage());
            return ApiResponse::errorResponse(false, 'An error occurred while processing the failed payment.');
        }
    }

    public function getWalletBalance()
    {
        $user = Auth::user();
        $balance = number_format((float) $user->wallet, 2, '.', '');
        return ApiResponse::sendResponse(true, 'Wallet details retrieved successfully', [
            'balance' => $balance,
        ]);
    }
    public function getAllWallets()
    {
        $user = Auth::user();
        $fullName = "{$user->first_name} {$user->last_name}";
        $wallets = Wallet::where('user_id', $user->id)
            ->where('status', 'success')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($wallet) use ($fullName) {
                return [
                    'id' => $wallet->id,
                    'user_id' => $wallet->user_id,
                    'amount' => $wallet->amount,
                    'status' => $wallet->status,
                    'type' => $wallet->type,
                    'created_at' => $wallet->created_at,
                    'full_name' => $fullName,
                ];
            });

        $lastTransaction = Wallet::where('user_id', $user->id)
            ->select('created_at', 'type')
            ->latest()
            ->first();
        $lastTransaction->created_at = $lastTransaction->created_at->format('Y-m-d');
        $lastTransaction['amount'] = round($user->wallet, 2);
        $balance['amount'] = number_format((float) $user->wallet, 2, '.', '');

        return ApiResponse::sendResponse(true, 'All wallets retrieved successfully', [
            'wallets' => $wallets,
            'balance' => $balance
        ]);

    }
}