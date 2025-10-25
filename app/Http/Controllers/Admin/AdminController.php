<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Inventory;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        // Get statistics
        $stats = [
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'total_customers' => User::whereHas('roles', function($query) {
                $query->where('name', '!=', 'admin');
            })->count(),
        ];

        // Get recent orders
        $recent_orders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Get low stock products
        $low_stock_products = Inventory::with('product')
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->where('track_stock', true)
            ->limit(5)
            ->get();

        // Get top products (by order items)
        $top_products = Product::with(['category', 'images'])
            ->withSum('orderItems', 'quantity')
            ->orderBy('order_items_sum_quantity', 'desc')
            ->limit(5)
            ->get();

        // Get recent customers
        $recent_customers = User::withCount('orders')
            ->whereHas('roles', function($query) {
                $query->where('name', '!=', 'admin');
            })
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recent_orders',
            'low_stock_products',
            'top_products',
            'recent_customers'
        ));
    }


    /**
     * Get dashboard statistics (AJAX)
     */
    public function getStats()
    {
        $stats = [
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'total_customers' => User::whereHas('roles', function($query) {
                $query->where('name', '!=', 'admin');
            })->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'low_stock_products' => Inventory::whereColumn('current_stock', '<=', 'minimum_stock')->count(),
            'out_of_stock_products' => Inventory::where('current_stock', '<=', 0)->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Get recent activity (AJAX)
     */
    public function getRecentActivity()
    {
        $activities = [
            'recent_orders' => Order::with('user')->latest()->limit(5)->get(),
            'recent_products' => Product::with('category')->latest()->limit(5)->get(),
            'recent_customers' => User::whereHas('roles', function($query) {
                $query->where('name', '!=', 'admin');
            })->latest()->limit(5)->get(),
        ];

        return response()->json([
            'success' => true,
            'activities' => $activities
        ]);
    }
}
