<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'location',
        'website',
        'bio',
        'email_verified_at',
        'phone_verified_at',
        'is_verified',
        'verification_method',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
        ];
    }

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->unread();
    }

    public function importantNotifications()
    {
        return $this->hasMany(Notification::class)->important();
    }

    public function otpVerifications()
    {
        return $this->hasMany(OtpVerification::class);
    }

    /**
     * Check if user is fully verified (email and phone)
     */
    public function isFullyVerified()
    {
        return $this->is_verified && $this->email_verified_at && $this->phone_verified_at;
    }

    /**
     * Check if email is verified
     */
    public function isEmailVerified()
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Check if phone is verified
     */
    public function isPhoneVerified()
    {
        return !is_null($this->phone_verified_at);
    }

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified()
    {
        $this->update([
            'email_verified_at' => now(),
            'is_verified' => $this->isPhoneVerified() || $this->verification_method === 'email',
        ]);
    }

    /**
     * Mark phone as verified
     */
    public function markPhoneAsVerified()
    {
        $this->update([
            'phone_verified_at' => now(),
            'is_verified' => $this->isEmailVerified() || $this->verification_method === 'sms',
        ]);
    }
}