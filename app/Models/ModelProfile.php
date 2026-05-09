<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelProfile extends Model
{
    protected $fillable = [
        'user_id', 'bio', 'country', 'languages', 'audio_price', 'video_price',
        'online_status', 'kyc_status', 'kyc_document', 'total_earnings',
        'pending_withdrawal', 'profile_photo', 'cover_photo', 'total_calls', 'rating',
    ];

    protected $casts = [
        'online_status' => 'boolean',
        'audio_price' => 'decimal:2',
        'video_price' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'pending_withdrawal' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo) {
            // External URL (Unsplash demo photos)
            if (str_starts_with($this->profile_photo, 'http')) {
                return $this->profile_photo;
            }
            return asset('storage/' . $this->profile_photo);
        }
        $colors = ['00b894','e17055','6c5ce7','fd79a8','0984e3','fdcb6e','e84393','00cec9'];
        $name = $this->user->name ?? 'Model';
        $color = $colors[abs(crc32($name)) % count($colors)];
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&size=300&background=' . $color . '&color=fff&bold=true&format=png';
    }

    public function getLanguagesArrayAttribute(): array
    {
        return $this->languages ? explode(',', $this->languages) : [];
    }
}
