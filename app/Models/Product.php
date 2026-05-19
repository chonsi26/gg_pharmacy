<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name', 'price', 'old_price', 'image',
        'badge', 'badge_type',
        // ── Product Details ──────────────────────────────────────────────
        'description', 'origin', 'product_usage', 'ingredients', 'warnings',
        // ── Dimensions ──────────────────────────────────────────────────
        'width', 'height', 'depth',
        // ── Relationships ────────────────────────────────────────────────
        'category_id', 'brand_id', 'section_id',
        'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price'     => 'float',
        'old_price' => 'float',
        'width'     => 'float',
        'height'    => 'float',
        'depth'     => 'float',
    ];

    // ── Relationships ───────────────────────────────────────────────────────

    /** The category this product belongs to (e.g. Healthcare, Allergy). */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** The brand that manufactures / markets this product. */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /** The display section/carousel this product lives in. */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    public function formattedPrice(): string
    {
        return '₱' . number_format($this->price, 2);
    }

    public function formattedOldPrice(): ?string
    {
        return $this->old_price ? '₱' . number_format($this->old_price, 2) : null;
    }

    public function isMostSold(): bool
    {
        return $this->badge_type === 'most-sold';
    }

    public function isSaleBadge(): bool
    {
        return $this->badge_type === 'sale-badge';
    }

    public function hasDiscount(): bool
    {
        return ! is_null($this->old_price) && $this->old_price > $this->price;
    }

    public function discountPercent(): ?int
    {
        if (! $this->hasDiscount()) {
            return null;
        }

        return (int) round((($this->old_price - $this->price) / $this->old_price) * 100);
    }

    public function hasDimensions(): bool
    {
        return ! is_null($this->width) || ! is_null($this->height) || ! is_null($this->depth);
    }
}