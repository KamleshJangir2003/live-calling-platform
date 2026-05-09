<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role',
        'wallet_balance', 'otp', 'otp_expires_at', 'phone_verified',
        'status', 'avatar', 'country',
    ];

    protected $hidden = ['password', 'remember_token', 'otp'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'phone_verified' => 'boolean',
        'wallet_balance' => 'decimal:2',
    ];

    public function modelProfile()
    {
        return $this->hasOne(ModelProfile::class);
    }

    public function callsMade()
    {
        return $this->hasMany(Call::class, 'caller_id');
    }

    public function callsReceived()
    {
        return $this->hasMany(Call::class, 'receiver_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedBy()
    {
        return $this->hasMany(Favorite::class, 'model_id');
    }

    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isModel(): bool
    {
        return $this->role === 'model';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function hasEnoughBalance(float $amount): bool
    {
        return $this->wallet_balance >= $amount;
    }

    public function deductBalance(float $amount): void
    {
        $this->decrement('wallet_balance', $amount);
    }

    public function addBalance(float $amount): void
    {
        $this->increment('wallet_balance', $amount);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $colors = ['00b894','e17055','6c5ce7','fd79a8','0984e3','fdcb6e'];
        $color = $colors[abs(crc32($this->name ?? 'U')) % count($colors)];
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? 'User') . '&size=200&background=' . $color . '&color=fff&bold=true&format=png';
    }
}
