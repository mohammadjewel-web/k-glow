<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send order confirmation notification
     */
    public static function sendOrderConfirmation(Order $order)
    {
        $notification = Notification::createOrderNotification(
            $order->user_id,
            $order->id,
            'order_confirmed',
            'Order Confirmed',
            "Your order #{$order->order_number} has been confirmed and is being processed.",
            [
                'order_number' => $order->order_number,
                'total_amount' => $order->total_amount,
                'status' => $order->status,
            ]
        );

        // Send email notification
        self::sendEmailNotification($order->user, $notification);
        
        return $notification;
    }

    /**
     * Send order shipped notification
     */
    public static function sendOrderShipped(Order $order)
    {
        $notification = Notification::createOrderNotification(
            $order->user_id,
            $order->id,
            'order_shipped',
            'Order Shipped',
            "Your order #{$order->order_number} has been shipped and is on its way to you.",
            [
                'order_number' => $order->order_number,
                'tracking_number' => $order->data['tracking_number'] ?? null,
                'estimated_delivery' => $order->data['estimated_delivery'] ?? null,
            ]
        );

        self::sendEmailNotification($order->user, $notification);
        
        return $notification;
    }

    /**
     * Send order delivered notification
     */
    public static function sendOrderDelivered(Order $order)
    {
        $notification = Notification::createOrderNotification(
            $order->user_id,
            $order->id,
            'order_delivered',
            'Order Delivered',
            "Your order #{$order->order_number} has been delivered successfully.",
            [
                'order_number' => $order->order_number,
                'delivery_date' => now()->format('Y-m-d'),
            ]
        );

        self::sendEmailNotification($order->user, $notification);
        
        return $notification;
    }

    /**
     * Send order cancelled notification
     */
    public static function sendOrderCancelled(Order $order, $reason = null)
    {
        $notification = Notification::createOrderNotification(
            $order->user_id,
            $order->id,
            'order_cancelled',
            'Order Cancelled',
            "Your order #{$order->order_number} has been cancelled." . ($reason ? " Reason: {$reason}" : ''),
            [
                'order_number' => $order->order_number,
                'cancellation_reason' => $reason,
                'refund_amount' => $order->total_amount,
            ]
        );

        self::sendEmailNotification($order->user, $notification);
        
        return $notification;
    }

    /**
     * Send payment confirmation notification
     */
    public static function sendPaymentConfirmation(Order $order)
    {
        $notification = Notification::createOrderNotification(
            $order->user_id,
            $order->id,
            'payment_confirmed',
            'Payment Confirmed',
            "Payment for order #{$order->order_number} has been confirmed.",
            [
                'order_number' => $order->order_number,
                'payment_method' => $order->payment_method,
                'amount_paid' => $order->total_amount,
            ]
        );

        self::sendEmailNotification($order->user, $notification);
        
        return $notification;
    }

    /**
     * Send product back in stock notification
     */
    public static function sendProductBackInStock(Product $product, User $user)
    {
        $notification = Notification::createProductNotification(
            $user->id,
            $product->id,
            'product_back_in_stock',
            'Product Back in Stock',
            "The product '{$product->name}' is now back in stock!",
            [
                'product_name' => $product->name,
                'product_url' => route('product.details', $product->slug),
                'stock_quantity' => $product->stock_quantity,
            ]
        );

        return $notification;
    }

    /**
     * Send price drop notification
     */
    public static function sendPriceDrop(Product $product, User $user, $oldPrice, $newPrice)
    {
        $discount = round((($oldPrice - $newPrice) / $oldPrice) * 100);
        
        $notification = Notification::createProductNotification(
            $user->id,
            $product->id,
            'price_drop',
            'Price Drop Alert',
            "The price of '{$product->name}' has dropped by {$discount}%! New price: ৳{$newPrice}",
            [
                'product_name' => $product->name,
                'product_url' => route('product.details', $product->slug),
                'old_price' => $oldPrice,
                'new_price' => $newPrice,
                'discount_percentage' => $discount,
            ]
        );

        return $notification;
    }

    /**
     * Send welcome notification to new users
     */
    public static function sendWelcomeNotification(User $user)
    {
        $notification = Notification::createSystemNotification(
            $user->id,
            'welcome',
            'Welcome to K-GLOW!',
            'Welcome to K-GLOW! Start exploring our amazing products and enjoy your shopping experience.',
            [
                'welcome_bonus' => 'Get 10% off your first order with code WELCOME10',
                'explore_url' => route('shop'),
            ],
            true
        );

        return $notification;
    }

    /**
     * Send system maintenance notification
     */
    public static function sendMaintenanceNotification($message, $startTime, $endTime)
    {
        $users = User::where('is_active', true)->get();
        
        foreach ($users as $user) {
            Notification::createSystemNotification(
                $user->id,
                'maintenance',
                'Scheduled Maintenance',
                $message,
                [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'maintenance_message' => $message,
                ],
                true
            );
        }
    }

    /**
     * Send promotional notification
     */
    public static function sendPromotionalNotification($title, $message, $users = null)
    {
        if (!$users) {
            $users = User::where('is_active', true)->get();
        }

        foreach ($users as $user) {
            Notification::createSystemNotification(
                $user->id,
                'promotional',
                $title,
                $message,
                [
                    'promotion_title' => $title,
                    'promotion_message' => $message,
                ]
            );
        }
    }

    /**
     * Send low stock notification to admins
     */
    public static function sendLowStockNotification($inventory)
    {
        $admins = User::role('admin')->get();
        
        foreach ($admins as $admin) {
            Notification::createProductNotification(
                $admin->id,
                $inventory->product_id,
                'low_stock_alert',
                'Low Stock Alert',
                "Product '{$inventory->product->name}' is running low on stock. Current: {$inventory->current_stock}, Minimum: {$inventory->minimum_stock}",
                [
                    'product_name' => $inventory->product->name,
                    'current_stock' => $inventory->current_stock,
                    'minimum_stock' => $inventory->minimum_stock,
                    'product_url' => route('admin.inventory.show', $inventory->id),
                ]
            );
        }
    }

    /**
     * Send out of stock notification to admins
     */
    public static function sendOutOfStockNotification($inventory)
    {
        $admins = User::role('admin')->get();
        
        foreach ($admins as $admin) {
            Notification::createProductNotification(
                $admin->id,
                $inventory->product_id,
                'out_of_stock_alert',
                'Out of Stock Alert',
                "Product '{$inventory->product->name}' is out of stock!",
                [
                    'product_name' => $inventory->product->name,
                    'current_stock' => $inventory->current_stock,
                    'product_url' => route('admin.inventory.show', $inventory->id),
                ]
            );
        }
    }

    /**
     * Send email notification
     */
    private static function sendEmailNotification(User $user, Notification $notification)
    {
        try {
            // This would integrate with your email service
            // For now, just log the notification
            Log::info("Email notification sent to {$user->email}: {$notification->title}");
            
            // Mark as sent
            $notification->markAsSent();
        } catch (\Exception $e) {
            Log::error("Failed to send email notification: " . $e->getMessage());
        }
    }

    /**
     * Get notification statistics
     */
    public static function getStatistics()
    {
        return [
            'total_notifications' => Notification::count(),
            'unread_notifications' => Notification::unread()->count(),
            'important_notifications' => Notification::important()->count(),
            'notifications_today' => Notification::whereDate('created_at', today())->count(),
            'notifications_this_week' => Notification::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];
    }
}
