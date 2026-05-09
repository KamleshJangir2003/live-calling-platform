<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Razorpay\Api\Api;

class WalletService
{
    private Api $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
    }

    public function createOrder(float $amount): array
    {
        $order = $this->razorpay->order->create([
            'amount' => (int)($amount * 100), // paise
            'currency' => 'INR',
            'receipt' => 'rcpt_' . time(),
        ]);

        return [
            'order_id' => $order->id,
            'amount' => $amount,
            'currency' => 'INR',
            'key' => config('services.razorpay.key'),
        ];
    }

    public function verifyAndCredit(User $user, string $orderId, string $paymentId, string $signature, float $amount): bool
    {
        try {
            $this->razorpay->utility->verifyPaymentSignature([
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature,
            ]);

            $balanceBefore = $user->wallet_balance;
            $user->addBalance($amount);

            Transaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'recharge',
                'status' => 'completed',
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'description' => "Wallet recharge of ₹{$amount}",
                'balance_before' => $balanceBefore,
                'balance_after' => $user->fresh()->wallet_balance,
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deductForCall(User $user, float $amount, int $callId): void
    {
        $balanceBefore = $user->wallet_balance;
        $user->deductBalance($amount);

        Transaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => 'call_deduction',
            'status' => 'completed',
            'description' => "Call charge deduction",
            'balance_before' => $balanceBefore,
            'balance_after' => $user->fresh()->wallet_balance,
            'call_id' => $callId,
        ]);
    }

    public function creditModelEarning(User $model, float $amount, int $callId): void
    {
        $commission = (float) \App\Models\Setting::get('commission_rate', 20);
        $earning = $amount * (1 - $commission / 100);

        $balanceBefore = $model->wallet_balance;
        $model->addBalance($earning);
        $model->modelProfile()->increment('total_earnings', $earning);

        Transaction::create([
            'user_id' => $model->id,
            'amount' => $earning,
            'type' => 'earning',
            'status' => 'completed',
            'description' => "Call earning (after {$commission}% commission)",
            'balance_before' => $balanceBefore,
            'balance_after' => $model->fresh()->wallet_balance,
            'call_id' => $callId,
        ]);
    }
}
