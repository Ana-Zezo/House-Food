<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Cart;
use App\Models\Chef;
use App\Models\Food;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\OrderItem;
use App\Trait\ApiResponse;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MyFatoorah\Library\MyFatoorah;
use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
use App\Http\Resources\Transaction\TransactionResource;

use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentEmbedded;


class OrderController extends Controller
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
    public function checkout(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $user = Auth::user();

        $cartItems = Cart::where('user_id', $user->id)->with('food')->get();
        // dd($cartItems);
        if ($cartItems->isEmpty()) {
            return ApiResponse::errorResponse(false, __('messages.cart_empty'));
        }

        $totalPrice = $cartItems->sum(
            fn($item) =>
            $item->food ?
            (($item->food->offer_price && $item->food->offer_price != 0.00)
                ? $item->food->offer_price
                : $item->food->price) * $item->qty
            : 0
        );

        $order = Order::create([
            'user_id' => $user->id,
            'address_id' => $request->address_id,
            'total_price' => $totalPrice,
            'paid' => 'pending',
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'food_id' => $item->food_id,
                'chef_id' => $item->food->chef_id,
                'qty' => $item->qty,
                'subtotal' => (($item->food->offer_price && $item->food->offer_price != 0.00)
                    ? $item->food->offer_price
                    : $item->food->price) * $item->qty,
                'chef_status' => 'pending',
                'chef_status_updated_at' => null
            ]);
        }

        Cart::where('user_id', $user->id)->delete();

        return ApiResponse::sendResponse(true, __('messages.Order_created_successfully_waiting_for_payment'), [
            'order' => $order
        ]);
    }


    // PayOrder
    public function payOrder(Request $request, $orderId)
    {
        $request->validate([
            'payment' => 'required|in:wallet,credit',
        ]);

        $user = Auth::user();


        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->where('paid', 'pending')
            ->first();

        if (!$order) {
            return ApiResponse::errorResponse(false, __('messages.Order_not_found_or_cannot_be_paid'));
        }

        if ($request->payment === 'wallet') {
            return DB::transaction(function () use ($order, $user) {
                if ($user->wallet < $order->total_price) {
                    return ApiResponse::errorResponse(false, __('messages.Insufficient_balance'));
                    // return response()->json(['error' => __('messages.Insufficient_balance')], 400);
                }

                $user->decrement('wallet', $order->total_price);

                Transaction::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'paymentId' => Str::uuid(),
                    'type' => 'wallet',
                    'amount' => $order->total_price,
                    'status' => 'success',
                ]);
                $order->paid = 'success';
                $order->save();
                $order->update(['paid' => 'success']);
                $order->orderItems()->update(['chef_status_updated_at' => now()]);
                $order->orderItems->chef_status_updated_at = now();
                $order->orderItems->save();

                // 🔹 إضافة سجل في جدول Wallet
                Wallet::create([
                    'user_id' => $user->id,
                    'amount' => $order->total_price,
                    'type' => 'payOrder',
                    'status' => 'success',
                ]);
                return ApiResponse::sendResponse(true, __('Order_paid_successfully_via_Wallet'));
            });
        }

        if ($request->payment === 'credit') {
            $mfObj = new MyFatoorahPayment($this->mfConfig);
            $data = [
                'InvoiceValue' => $order->total_price,
                'CustomerName' => $user->name,
                'CustomerEmail' => $user->email,
                'DisplayCurrencyIso' => "EGP",
                'CustomerReference' => $order->id,
                'MobileCountryCode' => '+20',
                'CustomerMobile' => $user->phone,
                'CallBackUrl' => route('payments.callback'),
                'ErrorUrl' => route('payments.error'),
                'Language' => app()->getLocale(),
            ];

            $paymentResponse = $mfObj->getInvoiceURL($data);
            if (!$paymentResponse || !isset($paymentResponse['invoiceURL'])) {
                return ApiResponse::errorResponse(false, __('messages.payment_initiation_failed'));
            }

            Transaction::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'amount' => $order->total_price,
                'type' => 'credit',
                'status' => 'pending',
                'paymentId' => $paymentResponse['invoiceId'],
            ]);

            return response()->json([
                'message' => 'Redirect to payment gateway',
                'paymentUrl' => $paymentResponse['invoiceURL'],
            ]);
        }
        return ApiResponse::errorResponse(false, __('messages.invalid_payment_method'));
    }

   
    public function paymentCallback(Request $request)
    {
        try {
            $paymentId = $request->query('paymentId');

            if (!$paymentId) {
                return view('payment.failed', ['message' => 'Payment ID is missing.']);
            }

            $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);

            $data = $mfObj->getPaymentStatus($paymentId, 'PaymentId');

            $transaction = Transaction::where('paymentId', $data->InvoiceId)->first();
            if (!$transaction) {
                return view('payment.failed', ['message' => 'Invalid payment ID.']);
            }
            // dd($mfObj, $data, $transaction);

            if ($data->InvoiceStatus === 'Paid') {
                DB::transaction(function () use ($transaction) {
                    $transaction->update(['status' => 'success']);
                    $order = $transaction->order;
                    $order->paid = "success";
                    $order->save;
                    $order->update(['paid' => 'success']);

                    $order->orderItems()->update([
                        'chef_status_updated_at' => now()
                    ]);
                });

                return view('payment.success');
            } else {
                $order = $transaction->order;
                $order->update(['paid' => 'failed']);
                $transaction->update(['status' => 'failed']);

                return view('payment.failed', ['message' => 'Payment failed or still pending.']);
            }

        } catch (\Exception $ex) {
            return view('payment.failed', ['message' => 'Error: ' . $ex->getMessage()]);
        }
    }
    public function myOrders(Request $request)
    {
        $user = Auth::user();

        $orderItems = OrderItem::with(['food:id,name,image'])
            ->select('id', 'order_id', 'food_id', 'chef_status', 'qty', 'subtotal')
            ->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('paid', 'success'); // شرط الدفع فقط
            })
            ->orderByDesc('created_at')
            ->get();

        return ApiResponse::sendResponse(true, __('messages.data_retrieved_successfully'), $orderItems);

    }


    public function cancelOrder(Request $request, $orderItemId)
    {
        $user = Auth::user();

        $orderItem = OrderItem::where('id', $orderItemId)
            ->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('paid', 'success')
                    ->whereHas('transactions', function ($query) {
                        $query->where('status', 'success');
                    });
            })
            ->first();

        if (!$orderItem) {
            return ApiResponse::errorResponse(false, __('messages.Order_item_cannot_be_canceled'));
        }

        // تحقق من حالة العنصر قبل الإلغاء (يجب أن يكون "pending")
        if ($orderItem->chef_status != 'pending') {
            return ApiResponse::errorResponse(false, __('messages.Item_is_not_in_a_pending_state_and_cannot_be_canceled'));
        }

        return DB::transaction(function () use ($orderItem, $user) {
            $refundAmount = $orderItem->subtotal;

            $orderItem->delete();

            Wallet::create([
                'user_id' => $user->id,
                'amount' => $refundAmount,
                'type' => 'cancelOrder',
                'status' => 'success',
            ]);

            // تحديث رصيد المحفظة للمستخدم
            $user->increment('wallet', $refundAmount);
            return ApiResponse::sendResponse(true, __('messages.Order_item_canceled_and_refunded_successfully'), ['wallet' => $user->wallet]);

        });
    }






    // Update Status In Chef
    public function updateOrderItemStatus(Request $request, $orderItemId)
    {
        $user = Auth::user();
        $orderItem = OrderItem::find($orderItemId);

        if (!$orderItem || $orderItem->chef_id != $user->id) {
            return ApiResponse::sendResponse(false, "Order item not found or unauthorized.");
        }

        $request->validate([
            'chef_status' => 'required|in:pending,accept,reject,complete',
        ]);


        $orderItem->update([
            'chef_status' => $request->chef_status,
            'chef_status_updated_at' => now(),
        ]);

        $order = $orderItem->order;
        $orderItems = $order->orderItems;

        if ($orderItems->where('chef_status', 'pending')->count() > 0) {
            $order->update(['status' => 'pending']);
        } elseif ($orderItems->where('chef_status', 'reject')->count() > 0) {
            $order->update(['status' => 'partially_rejected']);
        } elseif ($orderItems->where('chef_status', 'accept')->count() > 0) {
            $order->update(['status' => 'accepted']);
        } elseif ($orderItems->where('chef_status', 'complete')->count() === $orderItems->count()) {
            $order->update(['status' => 'complete']);

            foreach ($orderItems as $item) {
                $chef = $item->chef;
                if ($chef) {
                    $chef->increment('wallet', $item->subtotal);
                    $chef->increment('totalOrder', 1);
                }
            }
        }

        return ApiResponse::sendResponse(true, __('messages.order_updated_successfully'));
    }
    
    public function updateOrdersByStatus(Request $request, $id)
    {
        $chef = Auth::user();

        if (!$chef) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,accept,complete,reject'
        ]);

        $newStatus = $validated['status'];

        $orderItem = OrderItem::where('id', $id)
            ->where('chef_id', $chef->id)
            ->whereHas('order', function ($query) {
                $query->where('paid', 'success');
            })
            ->with('order.user') // تحميل بيانات المستخدم للطلب
            ->first();

        if (!$orderItem) {
            return response()->json(['message' => 'Order item not found or not authorized.'], 404);
        }

        $orderItem->update(['chef_status' => $newStatus]);

        if (in_array($newStatus, ['complete', 'reject'])) {
            $amount = $orderItem->subtotal;

            if ($newStatus === 'complete') {
                $chef->increment('wallet', $amount);
            }

            if ($newStatus === 'reject') {
                $user = $orderItem->order->user;

                $user->increment('wallet', $amount);

                Wallet::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'type' => 'chefCancelOrder',
                    'status' => 'success',
                ]);
            }
        }

        return response()->json([
            'message' => "Order item status updated to '{$newStatus}'.",
            'wallet' => $chef->wallet,
        ]);
    }
    public function getOrdersByStatus(Request $request)
    {
        $chef = Auth::user();

        if (!$chef) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,accept,complete,reject'
        ]);
        $status = $validated['status'];

        $orderItems = OrderItem::where('chef_id', $chef->id)
            ->where('chef_status', $status)
            ->whereHas('order', function ($query) {
                $query->where('paid', 'success');
            })
            ->with([
                'order:id,user_id,paid',
                'order.user:id,name,phone,image',
                'food:id,name,image',
                'chef:id,name,bio,image',
            ])
            ->orderBy('created_at', 'desc')
            ->get();
        // إحصائيات الشيف
        $chefStatistics = [
            'total_completed_orders' => OrderItem::where('chef_id', $chef->id)
                ->where('chef_status', 'complete')
                ->count(),
            'total_dishes' => $chef->food ? $chef->food->count() : 0,  
            'total_followers' => $chef->followers ? $chef->followers->count() : 0, 
        ];

        return response()->json([
            'status' => $status,
            'items_count' => $orderItems->count(),
            'order_items' => $orderItems,
            'chef_statistics' => $chefStatistics,
        ]);
    }


    private function changeTransactionStatus($inputData)
    {
        $orderId = $inputData['CustomerReference'];

        $invoiceId = $inputData['InvoiceId'];

        if ($inputData['TransactionStatus'] == 'SUCCESS') {
            $status = 'Paid';
            $error = '';
        } else {
            $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
            $data = $mfObj->getPaymentStatus($invoiceId, 'InvoiceId');

            $status = $data->InvoiceStatus;
            $error = $data->InvoiceError;
        }

        $message = $this->getTestMessage($status, $error);

        //4. Update order transaction status on your system
        return ['IsSuccess' => true, 'Message' => $message, 'Data' => $inputData];
    }



    //-----------------------------------------------------------------------------------------------------------------------------------------
    private function getTestMessage($status, $error)
    {
        if ($status == 'Paid') {
            return 'Invoice is paid.';
        } else if ($status == 'Failed') {
            return 'Invoice is not paid due to ' . $error;
        } else if ($status == 'Expired') {
            return $error;
        }
    }




    public function getCartDetails()
    {
        $user = Auth::user();

        $cartItems = $user->cartItems()->with('food')->get();

        if ($cartItems->isEmpty()) {
            return ApiResponse::sendResponse(false, "السلة فارغة", []);
        }

        $totalPrice = 0;

        $items = $cartItems->map(function ($item) use (&$totalPrice) {
            $food = $item->food;

            // استخدام سعر العرض لو موجود، وإلا السعر الأساسي
            $price = $food->offer_price ?? $food->price;

            $subtotal = $price * $item->qty;

            $totalPrice += $subtotal;

            return [
                'food' => new FoodResource($food),
                'qty' => $item->qty,
                'price_per_item' => number_format($price, 2),
                'subtotal' => number_format($subtotal, 2),
            ];
        });

        return ApiResponse::sendResponse(true, __('messages.data_retrieved_successfully'), [
            'items' => $items,
            'total_price' => number_format($totalPrice, 2),
        ]);
    }



}