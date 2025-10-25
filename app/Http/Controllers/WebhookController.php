<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Library\SslCommerz\SslCommerzNotification;

class WebhookController extends Controller
{
    /**
     * Handle SSLCommerz payment webhook
     */
    public function sslcommerzWebhook(Request $request)
    {
        try {
            Log::info('SSLCommerz webhook received:', [
                'method' => $request->method(),
                'all_data' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            $tran_id = $request->input('tran_id');
            $amount = $request->input('amount');
            $currency = $request->input('currency', 'BDT');
            $status = $request->input('status');

            if (!$tran_id || !$amount) {
                Log::error('SSLCommerz webhook missing required data:', [
                    'tran_id' => $tran_id,
                    'amount' => $amount
                ]);
                return response()->json(['error' => 'Missing required data'], 400);
            }

            // Extract order ID from transaction ID
            $order_id = explode('_', $tran_id)[0];
            $order = Order::find($order_id);

            if (!$order) {
                Log::error('SSLCommerz webhook order not found:', [
                    'tran_id' => $tran_id,
                    'order_id' => $order_id
                ]);
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Validate payment with SSLCommerz
            $sslcommerz = new SslCommerzNotification();
            $order_validate = $sslcommerz->orderValidate($tran_id, $amount, $currency, $request->all());

            Log::info('SSLCommerz webhook validation result:', [
                'tran_id' => $tran_id,
                'order_id' => $order_id,
                'validation_result' => $order_validate
            ]);

            if ($order_validate) {
                // Payment is valid, update order
                $order->update([
                    'payment_status' => 'completed',
                    'order_status' => 'confirmed',
                    'payment_reference' => $request->input('bank_tran_id'),
                    'payment_date' => now()
                ]);

                Log::info('SSLCommerz webhook order updated:', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'payment_status' => 'completed'
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Order updated successfully',
                    'order_id' => $order->id,
                    'order_number' => $order->order_number
                ]);
            } else {
                // Payment validation failed
                $order->update([
                    'payment_status' => 'failed',
                    'order_status' => 'cancelled'
                ]);

                Log::warning('SSLCommerz webhook payment validation failed:', [
                    'order_id' => $order->id,
                    'tran_id' => $tran_id
                ]);

                return response()->json([
                    'status' => 'failed',
                    'message' => 'Payment validation failed'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('SSLCommerz webhook error: ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Webhook processing error'
            ], 500);
        }
    }

    /**
     * Test webhook endpoint
     */
    public function testWebhook(Request $request)
    {
        Log::info('Test webhook received:', [
            'method' => $request->method(),
            'all_data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Test webhook received successfully',
            'timestamp' => now()->toISOString(),
            'data' => $request->all()
        ]);
    }
}



