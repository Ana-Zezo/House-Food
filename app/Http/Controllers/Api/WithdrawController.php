<?php

namespace App\Http\Controllers\Api;

use App\Models\Withdraw;
use App\Trait\ApiResponse;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\NotificationAdmin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{
    public function index(Request $request)
    {

        $chef = Auth::guard('chef')->user();
        $withdrawals = Withdraw::where('chef_id', $chef->id)->latest()->get();

        $walletBalance = $chef->wallet;

        return ApiResponse::sendResponse(true, 'Withdraw requests retrieved successfully', [
            'withdraws' => $withdrawals,
            'wallet' => $walletBalance
        ]);
    }
    public function store(Request $request)
    {
        $chef = Auth::guard('chef')->user();
        $amount = $request->input('amount');

        if (!$amount || $amount <= 0) {
            return ApiResponse::sendResponse(false, 'Invalid amount provided.');
        }

        if ($chef->wallet < $amount) {
            return ApiResponse::sendResponse(false, 'Your wallet balance is insufficient.');
        }

        $withdraw = Withdraw::create([
            'chef_id' => $chef->id,
            'amount' => $amount,
            'status' => 'pending',
        ]);

        $chef->wallet -= $amount;
        $chef->save();

        NotificationAdmin::create([
            'title' => $chef->name,
            'description' => "طلب سحب مبلغ  {$withdraw->amount}",
            'withdraw_id' => $withdraw->id,
        ]);

        return ApiResponse::sendResponse(true, 'Withdrawal request submitted');
    }





    // Approve withdraw request Admin
    public function updateWithdrawStatus(Request $request, Withdraw $withdraw)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        if ($withdraw->status !== 'pending') {
            return ApiResponse::sendResponse(false, 'This withdrawal request has already been processed.');
        }

        $withdraw->update(['status' => $request->status]);

        if ($request->status === 'rejected' && $withdraw->chef) {
            $chef = $withdraw->chef;
            $chef->increment('wallet', $withdraw->amount);
        }

        Notification::create([
            'notifiable_id' => $withdraw->chef->id,
            'notifiable_type' => get_class($withdraw->chef),
            'title' => 'طلب سحب',
            'description' => "طلب السحب الخاص بك بمبلغ {$withdraw->amount} تم " . ($withdraw->status === 'approved' ? 'قبوله' : 'رفضه') . ".",
            'is_read' => false
        ]);

        return ApiResponse::sendResponse(true, 'Withdrawal status updated');
    }



    public function AllWithdraws()
    {

        $withdraws = Withdraw::with('chef')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return ApiResponse::sendResponse(true, 'Withdraw requests retrieved successfully', $withdraws);
    }
}