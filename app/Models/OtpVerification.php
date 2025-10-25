<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OtpVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'identifier',
        'otp',
        'type',
        'expires_at',
        'is_verified',
        'verified_at',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    /**
     * Relationship: OTP belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a random 6-digit OTP
     */
    public static function generateOTP()
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Check if OTP has expired
     */
    public function isExpired()
    {
        return $this->expires_at < Carbon::now();
    }

    /**
     * Check if OTP is valid (not expired and not verified)
     */
    public function isValid()
    {
        return !$this->is_verified && !$this->isExpired();
    }

    /**
     * Mark OTP as verified
     */
    public function markAsVerified()
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => Carbon::now(),
        ]);
    }

    /**
     * Increment verification attempts
     */
    public function incrementAttempts()
    {
        $this->increment('attempts');
    }

    /**
     * Check if maximum attempts exceeded
     */
    public function maxAttemptsExceeded()
    {
        return $this->attempts >= 5; // Maximum 5 attempts
    }

    /**
     * Scope: Get valid OTP for identifier and type
     */
    public function scopeValid($query, $identifier, $type)
    {
        return $query->where('identifier', $identifier)
                     ->where('type', $type)
                     ->where('is_verified', false)
                     ->where('expires_at', '>', Carbon::now())
                     ->latest();
    }
}
