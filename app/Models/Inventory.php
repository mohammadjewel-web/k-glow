<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'product_id',
        'current_stock',
        'reserved_stock',
        'available_stock',
        'minimum_stock',
        'maximum_stock',
        'cost_price',
        'selling_price',
        'sku',
        'barcode',
        'location',
        'notes',
        'track_stock',
        'allow_backorder',
        'is_active',
        'last_restocked_at',
        'last_sold_at',
    ];

    protected $casts = [
        'current_stock' => 'integer',
        'reserved_stock' => 'integer',
        'available_stock' => 'integer',
        'minimum_stock' => 'integer',
        'maximum_stock' => 'integer',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'track_stock' => 'boolean',
        'allow_backorder' => 'boolean',
        'is_active' => 'boolean',
        'last_restocked_at' => 'datetime',
        'last_sold_at' => 'datetime',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('current_stock', '<=', 'minimum_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', '<=', 0);
    }

    public function scopeInStock($query)
    {
        return $query->where('current_stock', '>', 0);
    }

    public function scopeTrackStock($query)
    {
        return $query->where('track_stock', true);
    }

    // Methods
    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    public function isOutOfStock(): bool
    {
        return $this->current_stock <= 0;
    }

    public function canSell(int $quantity): bool
    {
        if (!$this->track_stock) {
            return true;
        }

        if ($this->allow_backorder) {
            return true;
        }

        return $this->available_stock >= $quantity;
    }

    public function updateStock(int $quantity, string $type, string $reason = null, $referenceType = null, $referenceId = null, $unitCost = null): void
    {
        $previousStock = $this->current_stock;
        $newStock = $previousStock + $quantity;

        // Update inventory
        $this->update([
            'current_stock' => $newStock,
            'available_stock' => $newStock - $this->reserved_stock,
            'last_sold_at' => $type === 'out' ? now() : $this->last_sold_at,
            'last_restocked_at' => $type === 'in' ? now() : $this->last_restocked_at,
        ]);

        // Create stock movement record
        $this->stockMovements()->create([
            'product_id' => $this->product_id,
            'type' => $type,
            'quantity' => $quantity,
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'reason' => $reason,
            'unit_cost' => $unitCost,
            'total_cost' => $unitCost ? $unitCost * abs($quantity) : null,
            'user_id' => auth()->id(),
            'movement_date' => now(),
        ]);
    }

    public function reserveStock(int $quantity): bool
    {
        if (!$this->canSell($quantity)) {
            return false;
        }

        $this->update([
            'reserved_stock' => $this->reserved_stock + $quantity,
            'available_stock' => $this->current_stock - ($this->reserved_stock + $quantity),
        ]);

        return true;
    }

    public function releaseReservedStock(int $quantity): void
    {
        $newReservedStock = max(0, $this->reserved_stock - $quantity);
        
        $this->update([
            'reserved_stock' => $newReservedStock,
            'available_stock' => $this->current_stock - $newReservedStock,
        ]);
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->isOutOfStock()) {
            return 'out_of_stock';
        }

        if ($this->isLowStock()) {
            return 'low_stock';
        }

        return 'in_stock';
    }

    public function getStockStatusColorAttribute(): string
    {
        return match($this->stock_status) {
            'out_of_stock' => 'red',
            'low_stock' => 'yellow',
            'in_stock' => 'green',
            default => 'gray'
        };
    }

    public function getStockValueAttribute(): float
    {
        return $this->current_stock * ($this->cost_price ?? 0);
    }

    public function getSellingValueAttribute(): float
    {
        return $this->current_stock * ($this->selling_price ?? 0);
    }

    // Static methods
    public static function createForProduct(Product $product, array $data = []): self
    {
        return self::create(array_merge([
            'product_id' => $product->id,
            'current_stock' => 0,
            'reserved_stock' => 0,
            'available_stock' => 0,
            'minimum_stock' => 0,
            'track_stock' => true,
            'allow_backorder' => false,
            'is_active' => true,
        ], $data));
    }

    public static function getLowStockProducts()
    {
        return self::active()
            ->trackStock()
            ->lowStock()
            ->with('product')
            ->get();
    }

    public static function getOutOfStockProducts()
    {
        return self::active()
            ->trackStock()
            ->outOfStock()
            ->with('product')
            ->get();
    }
}
