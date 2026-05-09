<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct(private WalletService $walletService) {}

    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('wallet.index', compact('transactions'));
    }

    public function createOrder(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:10|max:50000']);

        $order = $this->walletService->createOrder($request->amount);

        return response()->json($order);
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature' => 'required',
            'amount' => 'required|numeric',
        ]);

        $success = $this->walletService->verifyAndCredit(
            Auth::user(),
            $request->razorpay_order_id,
            $request->razorpay_payment_id,
            $request->razorpay_signature,
            $request->amount
        );

        if ($success) {
            return response()->json([
                'success' => true,
                'balance' => Auth::user()->fresh()->wallet_balance,
                'message' => 'Wallet recharged successfully!',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Payment verification failed.'], 422);
    }
}
