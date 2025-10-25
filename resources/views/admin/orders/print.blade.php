<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    @php
        use App\Models\Setting;
        $siteName = Setting::get('site_name', 'K-Glow');
        $logo = Setting::get('logo', 'admin-assets/logo.png');
    @endphp
    
    <title>Order #{{ $order->order_number }} - Print</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            .print-break { page-break-before: always; }
            body { margin: 0; padding: 10px; font-size: 12px; }
            .print-container { max-width: none; margin: 0; position: relative; }
            .print-header { margin-bottom: 15px; }
            .print-section { margin-bottom: 10px; }
            .print-table { font-size: 11px; }
            .print-table th, .print-table td { padding: 4px 6px; }
            .print-summary { margin-bottom: 10px; }
            .print-footer { margin-top: 10px; font-size: 10px; }
            .print-logo { height: 40px; margin-bottom: 10px; }
            .print-title { font-size: 18px; margin-bottom: 5px; }
            .print-subtitle { font-size: 14px; margin-bottom: 3px; }
            .print-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px; }
            .print-card { padding: 8px; margin-bottom: 5px; }
            .print-card h3 { font-size: 14px; margin-bottom: 5px; }
            .print-items { margin-bottom: 8px; }
            .print-item { margin-bottom: 3px; }
            
            /* Content styling for watermark visibility */
            .print-container > * {
                position: relative;
                z-index: 2;
            }
            
            /* Watermark */
            .watermark {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                width: 667px;
                height: 300px;
                opacity: 0.08;
                z-index: 999;
                pointer-events: none;
            }
        }
        @media screen {
            .print-container { max-width: 800px; margin: 0 auto; padding: 20px; position: relative; }
            
            /* Content styling for watermark visibility */
            .print-container > * {
                position: relative;
                z-index: 2;
            }
            
            .watermark {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                width: 667px;
                height: 300px;
                opacity: 0.08;
                z-index: 999;
                pointer-events: none;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="print-container bg-white shadow-lg">
        <!-- Watermark -->
        <img src="{{ asset($logo) }}" alt="Watermark" class="watermark">
        
        <!-- Print Controls (Hidden when printing) -->
        <div class="no-print bg-gray-100 p-4 mb-6 rounded-lg">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-700">Order Print Preview</h2>
                <div class="flex space-x-3">
                    <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-print mr-2"></i> Print
                    </button>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Order
                    </a>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center mb-8 print-header">
            <!-- Logo -->
            <!-- <div class="mb-6">
                <img src="{{ asset($logo) }}" alt="{{ $siteName }}" class="h-16 mx-auto print-logo" onerror="this.style.display='none';">
            </div> -->
            <h1 class="text-3xl font-bold text-gray-900 mb-2 print-title">Order Receipt</h1>
            <p class="text-gray-600 print-subtitle">Order #{{ $order->order_number }}</p>
            <p class="text-sm text-gray-500">Date: {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>

        <!-- Order Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 print-grid print-section">
            <!-- Customer Information -->
            <div class="bg-gray-50 p-6 rounded-lg print-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Customer Information</h3>
                <div class="space-y-2 print-items">
                    <div class="flex justify-between print-item">
                        <span class="text-gray-600">Name:</span>
                        <span class="font-medium">{{ $order->user->name }}</span>
                    </div>
                    <div class="flex justify-between print-item">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium">{{ $order->user->email }}</span>
                    </div>
                    @if($order->phone)
                    <div class="flex justify-between print-item">
                        <span class="text-gray-600">Phone:</span>
                        <span class="font-medium">{{ $order->phone }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Status -->
            <div class="bg-gray-50 p-6 rounded-lg print-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Order Status</h3>
                <div class="space-y-2 print-items">
                    <div class="flex justify-between print-item">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->status_color }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between print-item">
                        <span class="text-gray-600">Payment:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->payment_status_color }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <div class="flex justify-between print-item">
                        <span class="text-gray-600">Method:</span>
                        <span class="font-medium">{{ ucfirst($order->payment_method) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Address -->
        @if($order->shipping_address)
        <div class="mb-8 print-section">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Shipping Address</h3>
            <div class="bg-gray-50 p-6 rounded-lg print-card">
                <pre class="text-gray-700 whitespace-pre-wrap">{{ $order->shipping_address }}</pre>
            </div>
        </div>
        @endif

        <!-- Order Items -->
        <div class="mb-8 print-section">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Order Items</h3>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 print-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($item->product && $item->product->thumbnail)
                                        <img src="{{ asset('admin-assets/products/' . $item->product->thumbnail) }}" 
                                             class="w-12 h-12 rounded-lg object-cover mr-3 shadow-md" alt="Product">
                                    @elseif($item->product && $item->product->images && $item->product->images->count() > 0 && $item->product->images->first())
                                        <img src="{{ asset('admin-assets/products/' . $item->product->images->first()->image) }}" 
                                             class="w-12 h-12 rounded-lg object-cover mr-3 shadow-md" alt="Product">
                                    @else
                                        <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-3 shadow-md">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $item->product_name }}</div>
                                        @if($item->product_options)
                                            <div class="text-xs text-gray-500">
                                                @foreach($item->product_options as $key => $value)
                                                    <span class="inline-block bg-gray-100 px-2 py-1 rounded mr-1 mb-1">
                                                        {{ ucfirst($key) }}: {{ $value }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ৳{{ number_format($item->price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                ৳{{ number_format($item->price * $item->quantity, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-gray-50 p-6 rounded-lg print-summary">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Order Summary</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-medium">৳{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->tax_amount > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Tax:</span>
                    <span class="font-medium">৳{{ number_format($order->tax_amount, 2) }}</span>
                </div>
                @endif
                @if($order->shipping_amount > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Shipping:</span>
                    <span class="font-medium">৳{{ number_format($order->shipping_amount, 2) }}</span>
                </div>
                @endif
                @if($order->discount_amount > 0)
                <div class="flex justify-between text-sm text-red-600">
                    <span>Discount:</span>
                    <span class="font-medium">-৳{{ number_format($order->discount_amount, 2) }}</span>
                </div>
                @endif
                <div class="border-t border-gray-200 pt-3">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total:</span>
                        <span class="text-orange-600">৳{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($order->notes)
        <div class="mt-8 print-section">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Order Notes</h3>
            <div class="bg-gray-50 p-6 rounded-lg print-card">
                <p class="text-gray-700">{{ $order->notes }}</p>
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="mt-12 text-center text-sm text-gray-500 border-t border-gray-200 pt-6 print-footer">
            <p>Thank you for your order!</p>
            <p class="mt-2">This receipt was generated on {{ now()->format('M d, Y \a\t h:i A') }}</p>
        </div>
    </div>

    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</body>
</html>
