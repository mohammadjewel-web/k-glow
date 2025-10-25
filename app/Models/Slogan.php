<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slogan extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get active slogans ordered by order column
     */
    public static function getActiveSlogans()
    {
        return self::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();
    }

    /**
     * Scope for active slogans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered slogans
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
