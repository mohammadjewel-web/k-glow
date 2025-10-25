<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of orders with filtering
     */
    public function index(Request $request)
    {
        $query = Order::with('user');

        // Search by order number or customer name/email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by amount range
        if ($request->filled('amount_from')) {
            $query->where('total_amount', '>=', $request->amount_from);
        }

        if ($request->filled('amount_to')) {
            $query->where('total_amount', '<=', $request->amount_to);
        }

        // Sort orders
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['order_number', 'status', 'payment_status', 'total_amount', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $orders = $query->paginate(20)->withQueryString();

        // Get filter options for dropdowns
        $statusOptions = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed', 
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled'
        ];

        $paymentStatusOptions = [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
            'refunded' => 'Refunded'
        ];

        return view('admin.orders.index', compact(
            'orders', 
            'statusOptions', 
            'paymentStatusOptions'
        ));
    }

    /**
     * Display the specified order
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.product.images'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'notes' => 'nullable|string|max:1000'
        ]);

        $order = Order::findOrFail($id);
        
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'notes' => $request->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully!'
        ]);
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $query = Order::with('user');

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        $filename = 'orders_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Order Number',
                'Customer Name', 
                'Customer Email',
                'Status',
                'Payment Status',
                'Total Amount',
                'Created At'
            ]);

            // CSV data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name,
                    $order->user->email,
                    $order->status,
                    $order->payment_status,
                    $order->total_amount,
                    $order->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print order
     */
    public function print($id)
    {
        $order = Order::with(['user', 'items.product.images'])->findOrFail($id);
        return view('admin.orders.print', compact('order'));
    }
}
