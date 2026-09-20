<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'supplier',
        'quantity',
        'unit_cost',
        'manufacturing_date',
        'expiry_date',
        'is_active',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_cost' => 'decimal:2',
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * The product this stock entry belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope: stock items that have already expired.
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiry_date')->where('expiry_date', '<', now());
    }

    /**
     * Scope: stock items expiring within the given number of days (default 30).
     */
    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [now(), now()->addDays($days)]);
    }

    /**
     * Scope: only stock with quantity remaining.
     */
    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }
}