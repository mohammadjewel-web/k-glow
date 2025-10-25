<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display payment list
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->search . '%')
                  ->orWhere('transaction_id', 'like', '%' . $request->search . '%');
            });
        }

        // Payment method filter
        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        // Payment status filter
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->latest()->paginate(20)->withQueryString();

        // Get statistics
        $stats = [
            'total_payments' => Order::count(),
            'completed_payments' => Order::where('payment_status', 'completed')->count(),
            'pending_payments' => Order::where('payment_status', 'pending')->count(),
            'failed_payments' => Order::where('payment_status', 'failed')->count(),
            'total_revenue' => Order::where('payment_status', 'completed')->sum('total_amount'),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    /**
     * Show payment details
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.payments.show', compact('order'));
    }

    /**
     * Update payment status
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Debug: Log the incoming request data
        \Log::info('Payment Status Update Request:', [
            'order_id' => $order->id,
            'all_data' => $request->all(),
            'status_value' => $request->input('status'),
            'is_ajax' => $request->ajax()
        ]);

        $request->validate([
            'status' => 'required|in:pending,completed,failed,cancelled,refunded'
        ]);

        $order->update([
            'payment_status' => $request->status
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Payment status updated successfully');
    }

    /**
     * Export payments
     */
    public function export(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        // Apply same filters as index
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        $payments = $query->latest()->get();

        $filename = 'payments_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Order ID',
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Payment Method',
                'Payment Status',
                'Order Status',
                'Total Amount',
                'Transaction ID',
                'Payment Date',
                'Created At'
            ]);

            // CSV data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->order_number,
                    $payment->customer_name,
                    $payment->customer_email,
                    $payment->payment_method,
                    $payment->payment_status,
                    $payment->order_status,
                    $payment->total_amount,
                    $payment->transaction_id,
                    $payment->payment_date,
                    $payment->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get payment statistics
     */
    public function getStats()
    {
        $stats = [
            'total_payments' => Order::count(),
            'completed_payments' => Order::where('payment_status', 'completed')->count(),
            'pending_payments' => Order::where('payment_status', 'pending')->count(),
            'failed_payments' => Order::where('payment_status', 'failed')->count(),
            'total_revenue' => Order::where('payment_status', 'completed')->sum('total_amount'),
            'today_revenue' => Order::where('payment_status', 'completed')
                ->whereDate('created_at', today())
                ->sum('total_amount'),
            'monthly_revenue' => Order::where('payment_status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
        ];

        // Payment method breakdown
        $paymentMethods = Order::select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        return response()->json([
            'stats' => $stats,
            'payment_methods' => $paymentMethods
        ]);
    }
}
