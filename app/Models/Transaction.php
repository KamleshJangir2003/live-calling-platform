<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'amount', 'type', 'status', 'razorpay_order_id',
        'razorpay_payment_id', 'description', 'balance_before', 'balance_after', 'call_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function call()
    {
        return $this->belongsTo(Call::class);
    }
}
