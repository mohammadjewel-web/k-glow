<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'inventory_id',
        'type',
        'quantity',
        'previous_stock',
        'new_stock',
        'reference_type',
        'reference_id',
        'reason',
        'notes',
        'unit_cost',
        'total_cost',
        'user_id',
        'movement_date',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'previous_stock' => 'integer',
        'new_stock' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'movement_date' => 'datetime',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeInbound($query)
    {
        return $query->where('type', 'in');
    }

    public function scopeOutbound($query)
    {
        return $query->where('type', 'out');
    }

    public function scopeAdjustments($query)
    {
        return $query->where('type', 'adjustment');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('movement_date', [$startDate, $endDate]);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Methods
    public function isInbound(): bool
    {
        return $this->type === 'in';
    }

    public function isOutbound(): bool
    {
        return $this->type === 'out';
    }

    public function isAdjustment(): bool
    {
        return $this->type === 'adjustment';
    }

    public function getMovementTypeLabelAttribute(): string
    {
        return match($this->type) {
            'in' => 'Stock In',
            'out' => 'Stock Out',
            'adjustment' => 'Adjustment',
            'transfer' => 'Transfer',
            'return' => 'Return',
            'damage' => 'Damage',
            'expired' => 'Expired',
            default => ucfirst($this->type)
        };
    }

    public function getMovementTypeColorAttribute(): string
    {
        return match($this->type) {
            'in' => 'green',
            'out' => 'red',
            'adjustment' => 'blue',
            'transfer' => 'purple',
            'return' => 'orange',
            'damage' => 'red',
            'expired' => 'gray',
            default => 'gray'
        };
    }

    public function getAbsoluteQuantityAttribute(): int
    {
        return abs($this->quantity);
    }

    public function getNetQuantityAttribute(): int
    {
        return $this->isInbound() ? $this->quantity : -$this->quantity;
    }

    // Static methods
    public static function getTotalInbound($productId = null, $startDate = null, $endDate = null): int
    {
        $query = self::inbound();
        
        if ($productId) {
            $query->byProduct($productId);
        }
        
        if ($startDate && $endDate) {
            $query->byDateRange($startDate, $endDate);
        }
        
        return $query->sum('quantity');
    }

    public static function getTotalOutbound($productId = null, $startDate = null, $endDate = null): int
    {
        $query = self::outbound();
        
        if ($productId) {
            $query->byProduct($productId);
        }
        
        if ($startDate && $endDate) {
            $query->byDateRange($startDate, $endDate);
        }
        
        return $query->sum('quantity');
    }

    public static function getNetMovement($productId = null, $startDate = null, $endDate = null): int
    {
        $inbound = self::getTotalInbound($productId, $startDate, $endDate);
        $outbound = self::getTotalOutbound($productId, $startDate, $endDate);
        
        return $inbound - $outbound;
    }

    public static function getMovementSummary($productId = null, $startDate = null, $endDate = null): array
    {
        $query = self::query();
        
        if ($productId) {
            $query->byProduct($productId);
        }
        
        if ($startDate && $endDate) {
            $query->byDateRange($startDate, $endDate);
        }
        
        return [
            'total_movements' => $query->count(),
            'inbound' => $query->clone()->inbound()->sum('quantity'),
            'outbound' => $query->clone()->outbound()->sum('quantity'),
            'adjustments' => $query->clone()->adjustments()->sum('quantity'),
            'net_movement' => $query->clone()->inbound()->sum('quantity') - $query->clone()->outbound()->sum('quantity'),
        ];
    }
}
