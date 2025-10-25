<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InventoryController extends Controller
{
    /**
     * Display inventory dashboard
     */
    public function index(Request $request)
    {
        $query = Inventory::with(['product.images', 'product.category', 'product.brand'])->active();

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'low_stock':
                    $query->lowStock();
                    break;
                case 'out_of_stock':
                    $query->outOfStock();
                    break;
                case 'in_stock':
                    $query->inStock();
                    break;
            }
        }

        if ($request->filled('category')) {
            $query->whereHas('product.category', function($q) use ($request) {
                $q->where('id', $request->category);
            });
        }

        if ($request->filled('brand')) {
            $query->whereHas('product.brand', function($q) use ($request) {
                $q->where('id', $request->brand);
            });
        }

        $inventory = $query->paginate(20);

        // Statistics
        $stats = [
            'total_products' => Inventory::active()->count(),
            'low_stock_products' => Inventory::active()->lowStock()->count(),
            'out_of_stock_products' => Inventory::active()->outOfStock()->count(),
            'total_stock_value' => Inventory::active()->sum(DB::raw('current_stock * cost_price')),
            'total_selling_value' => Inventory::active()->sum(DB::raw('current_stock * selling_price')),
        ];

        return view('admin.inventory.index', compact('inventory', 'stats'));
    }

    /**
     * Show inventory details
     */
    public function show(Inventory $inventory)
    {
        $inventory->load(['product.images', 'product.category', 'product.brand', 'stockMovements' => function($query) {
            $query->with('user')->latest()->limit(50);
        }]);

        // Get movement statistics
        $movementStats = [
            'total_movements' => $inventory->stockMovements()->count(),
            'total_in' => $inventory->stockMovements()->where('type', 'in')->sum('quantity'),
            'total_out' => abs($inventory->stockMovements()->where('type', 'out')->sum('quantity')),
            'total_adjustments' => $inventory->stockMovements()->where('type', 'adjustment')->sum('quantity'),
            'recent_movements' => $inventory->stockMovements()->with('user')->latest()->limit(20)->get()
        ];

        return view('admin.inventory.show', compact('inventory', 'movementStats'));
    }

    /**
     * Update inventory stock
     */
    public function updateStock(Request $request, Inventory $inventory): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer',
            'type' => 'required|in:in,out,adjustment',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'unit_cost' => 'nullable|numeric|min:0',
        ]);

        $quantity = $request->quantity;
        $type = $request->type;

        // For outbound movements, quantity should be negative
        if ($type === 'out') {
            $quantity = -abs($quantity);
        }

        // Check if we can make the movement
        if ($type === 'out' && !$inventory->canSell(abs($quantity))) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock for this movement'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $inventory->updateStock(
                $quantity,
                $type,
                $request->reason,
                'manual_adjustment',
                null,
                $request->unit_cost
            );

            // Send low stock notification if needed
            if ($inventory->fresh()->isLowStock()) {
                $this->sendLowStockNotification($inventory);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully',
                'inventory' => $inventory->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk stock update
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $request->validate([
            'updates' => 'required|array',
            'updates.*.inventory_id' => 'required|exists:inventory,id',
            'updates.*.quantity' => 'required|integer',
            'updates.*.type' => 'required|in:in,out,adjustment',
            'updates.*.reason' => 'nullable|string|max:255',
        ]);

        $results = [];
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($request->updates as $update) {
                $inventory = Inventory::find($update['inventory_id']);
                $quantity = $update['type'] === 'out' ? -abs($update['quantity']) : $update['quantity'];

                if ($update['type'] === 'out' && !$inventory->canSell(abs($update['quantity']))) {
                    $errors[] = "Insufficient stock for {$inventory->product->name}";
                    continue;
                }

                $inventory->updateStock(
                    $quantity,
                    $update['type'],
                    $update['reason'] ?? null,
                    'bulk_update',
                    null
                );

                $results[] = [
                    'inventory_id' => $inventory->id,
                    'product_name' => $inventory->product->name,
                    'new_stock' => $inventory->fresh()->current_stock
                ];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bulk update completed',
                'results' => $results,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Bulk update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get low stock products
     */
    public function lowStock(): JsonResponse
    {
        $products = Inventory::getLowStockProducts();

        return response()->json([
            'success' => true,
            'products' => $products,
            'count' => $products->count()
        ]);
    }

    /**
     * Get out of stock products
     */
    public function outOfStock(): JsonResponse
    {
        $products = Inventory::getOutOfStockProducts();

        return response()->json([
            'success' => true,
            'products' => $products,
            'count' => $products->count()
        ]);
    }

    /**
     * Get inventory statistics
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_products' => Inventory::active()->count(),
            'low_stock_products' => Inventory::active()->lowStock()->count(),
            'out_of_stock_products' => Inventory::active()->outOfStock()->count(),
            'total_stock_value' => Inventory::active()->sum(DB::raw('current_stock * cost_price')),
            'total_selling_value' => Inventory::active()->sum(DB::raw('current_stock * selling_price')),
            'recent_movements' => StockMovement::with(['product', 'user'])
                ->latest()
                ->limit(10)
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'statistics' => $stats
        ]);
    }

    /**
     * Get stock movements for a product
     */
    public function movements(Request $request, Inventory $inventory)
    {
        $query = $inventory->stockMovements()->with(['user']);

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->byDateRange($request->date_from, $request->date_to);
        }

        $movements = $query->latest()->paginate(20);

        return response()->json([
            'success' => true,
            'movements' => $movements
        ]);
    }

    /**
     * Show all stock movements history
     */
    public function history(Request $request)
    {
        $query = \App\Models\StockMovement::with(['product.images', 'user']);

        // Filter by product
        if ($request->filled('product')) {
            $search = $request->product;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['created_at', 'quantity', 'type'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $movements = $query->paginate(20)->withQueryString();

        // Statistics
        $stats = [
            'total_in' => \App\Models\StockMovement::where('type', 'in')->sum('quantity'),
            'total_out' => abs(\App\Models\StockMovement::where('type', 'out')->sum('quantity')),
            'total_adjustments' => \App\Models\StockMovement::where('type', 'adjustment')->sum('quantity'),
        ];

        return view('admin.inventory.history', compact('movements', 'stats'));
    }

    /**
     * Create inventory for a product
     */
    public function createForProduct(Product $product): JsonResponse
    {
        if ($product->inventory) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory already exists for this product'
            ], 422);
        }

        $inventory = Inventory::createForProduct($product);

        return response()->json([
            'success' => true,
            'message' => 'Inventory created successfully',
            'inventory' => $inventory
        ]);
    }

    /**
     * Update inventory settings
     */
    public function updateSettings(Request $request, Inventory $inventory): JsonResponse
    {
        $request->validate([
            'minimum_stock' => 'required|integer|min:0',
            'maximum_stock' => 'nullable|integer|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'track_stock' => 'boolean',
            'allow_backorder' => 'boolean',
        ]);

        $inventory->update($request->only([
            'minimum_stock',
            'maximum_stock',
            'cost_price',
            'selling_price',
            'location',
            'notes',
            'track_stock',
            'allow_backorder'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Inventory settings updated successfully',
            'inventory' => $inventory->fresh()
        ]);
    }

    /**
     * Send low stock notification
     */
    private function sendLowStockNotification(Inventory $inventory): void
    {
        if ($inventory->isLowStock()) {
            NotificationService::sendLowStockNotification($inventory);
        }
        
        if ($inventory->isOutOfStock()) {
            NotificationService::sendOutOfStockNotification($inventory);
        }
    }
}
