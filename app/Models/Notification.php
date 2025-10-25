<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'data',
        'user_id',
        'order_id',
        'product_id',
        'channel',
        'is_read',
        'read_at',
        'is_sent',
        'sent_at',
        'is_important',
        'expires_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'is_sent' => 'boolean',
        'is_important' => 'boolean',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByChannel($query, $channel)
    {
        return $query->where('channel', $channel);
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', Carbon::now());
        });
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => Carbon::now(),
        ]);
    }

    public function markAsSent()
    {
        $this->update([
            'is_sent' => true,
            'sent_at' => Carbon::now(),
        ]);
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Static methods for creating notifications
    public static function createOrderNotification($userId, $orderId, $type, $title, $message, $data = [])
    {
        return self::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'user_id' => $userId,
            'order_id' => $orderId,
            'channel' => 'in_app',
            'is_important' => in_array($type, ['order_confirmed', 'order_shipped', 'order_delivered']),
        ]);
    }

    public static function createProductNotification($userId, $productId, $type, $title, $message, $data = [])
    {
        return self::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'user_id' => $userId,
            'product_id' => $productId,
            'channel' => 'in_app',
            'is_important' => false,
        ]);
    }

    public static function createSystemNotification($userId, $type, $title, $message, $data = [], $important = false)
    {
        return self::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'user_id' => $userId,
            'channel' => 'in_app',
            'is_important' => $important,
        ]);
    }
}
