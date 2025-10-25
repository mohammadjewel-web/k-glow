<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'order_status',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'shipping_address',
        'billing_address',
        'customer_name',
        'customer_email',
        'customer_phone',
        'phone',
        'city',
        'state',
        'postal_code',
        'transaction_id',
        'payment_reference',
        'payment_date',
        'notes',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Generate unique order number
    public static function generateOrderNumber()
    {
        do {
            $orderNumber = 'ORD-' . strtoupper(Str::random(8));
        } while (self::where('order_number', $orderNumber)->exists());
        
        return $orderNumber;
    }

    // Check if order can be cancelled
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    // Get status badge color
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'processing' => 'blue',
            'shipped' => 'purple',
            'delivered' => 'green',
            'cancelled' => 'red',
            'refunded' => 'gray',
            default => 'gray'
        };
    }

    // Get payment status badge color
    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            'pending' => 'yellow',
            'paid' => 'green',
            'failed' => 'red',
            'refunded' => 'gray',
            'cancelled' => 'red',
            default => 'gray'
        };
    }
}
