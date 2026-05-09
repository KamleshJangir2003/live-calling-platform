<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $fillable = [
        'caller_id', 'receiver_id', 'call_type', 'status', 'duration',
        'amount', 'price_per_minute', 'channel_name', 'agora_token',
        'started_at', 'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'amount' => 'decimal:2',
        'price_per_minute' => 'decimal:2',
    ];

    public function caller()
    {
        return $this->belongsTo(User::class, 'caller_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getDurationFormattedAttribute(): string
    {
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
