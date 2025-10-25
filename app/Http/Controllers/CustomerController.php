<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display customer dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get customer statistics
        $totalOrders = $user->orders()->count();
        $totalSpent = $user->orders()->where('status', '!=', 'cancelled')->sum('total_amount');
        $pendingOrders = $user->orders()->whereIn('status', ['pending', 'processing'])->count();
        $deliveredOrders = $user->orders()->where('status', 'delivered')->count();
        
        // Get recent orders
        $recentOrders = $user->orders()
            ->with('items.product')
            ->latest()
            ->take(5)
            ->get();
        
        // Get order status counts
        $orderStatusCounts = $user->orders()
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');
        
        // Get monthly spending for the last 6 months
        $monthlySpending = $user->orders()
            ->where('status', '!=', 'cancelled')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('customer.dashboard', compact(
            'user',
            'totalOrders',
            'totalSpent',
            'pendingOrders',
            'deliveredOrders',
            'recentOrders',
            'orderStatusCounts',
            'monthlySpending'
        ));
    }

    /**
     * Display customer orders
     */
    public function orders(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status');
        
        $query = $user->orders()->with('items.product');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $orders = $query->latest()->paginate(10);
        
        return view('customer.orders', compact('orders', 'status'));
    }

    /**
     * Display single order details
     */
    public function orderDetails($id)
    {
        $order = Auth::user()->orders()
            ->with(['items.product', 'user'])
            ->findOrFail($id);
        
        return view('customer.order-details', compact('order'));
    }

    /**
     * Cancel an order
     */
    public function cancelOrder(Request $request, $id)
    {
        $order = Auth::user()->orders()->findOrFail($id);
        
        if (!$order->canBeCancelled()) {
            return redirect()->back()->with('error', 'This order cannot be cancelled.');
        }
        
        $order->update([
            'status' => 'cancelled',
            'notes' => $request->cancellation_reason ?? 'Cancelled by customer'
        ]);
        
        // Restore inventory for cancelled order
        foreach ($order->items as $item) {
            if ($item->product->inventory && $item->product->inventory->track_stock) {
                $item->product->inventory->updateStock(
                    $item->quantity, // Positive for inbound (restore)
                    'return',
                    'Order cancelled: #' . $order->order_number,
                    'order_cancellation',
                    $order->id
                );
            }
        }
        
        // Send order cancelled notification
        NotificationService::sendOrderCancelled($order, $request->cancellation_reason);
        
        return redirect()->back()->with('success', 'Order cancelled successfully.');
    }

    /**
     * Display customer profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    /**
     * Update customer profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
        ]);
        
        $user->update($request->only(['name', 'email', 'phone']));
        
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
