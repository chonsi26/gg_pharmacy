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
     * Automatically include the computed `status` in array/JSON output
     * (used by the stocks table + products modal on the frontend).
     */
    protected $appends = ['status'];

    /**
     * The single, human-facing status for this stock batch. Priority order
     * (most urgent first): Expired > Out of Stock > Active > Inactive. This
     * keeps every batch in exactly one bucket, matching the stocks page
     * filter options (Active, Inactive, Out of Stock, Expired, All Stocks).
     */
    public function getStatusAttribute(): string
    {
        if ($this->expiry_date && $this->expiry_date->lt(now())) {
            return 'Expired';
        }

        if ($this->quantity <= 0) {
            return 'Out of Stock';
        }

        return $this->is_active ? 'Active' : 'Inactive';
    }

    /**
     * A human-friendly, stable batch number derived from the primary key
     * (e.g. "STK-00042"). Generated on the fly rather than stored, so it
     * always matches the real database id.
     */
    public function stockNo(): string
    {
        return 'STK-' . str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Whether this stock is currently a usable, ongoing "active" batch —
     * i.e. it is flagged active, still has quantity, and has not expired.
     * Used to enforce the "only one ongoing active batch per product" rule.
     */
    public function isOngoingActive(): bool
    {
        return $this->is_active
            && $this->quantity > 0
            && (! $this->expiry_date || $this->expiry_date->gte(now()));
    }

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