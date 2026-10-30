<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name', 'generic_name', 'form', 'price', 'old_price', 'image',
        'badge', 'badge_type',
        // ── Product Details ──────────────────────────────────────────────
        'description', 'origin', 'product_usage', 'ingredients', 'warnings',
        'requires_prescription',
        // ── Dimensions ──────────────────────────────────────────────────
        'width', 'height', 'depth',
        // ── Relationships ────────────────────────────────────────────────
        'category_id', 'brand_id', 'section_id',
        'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active'             => 'boolean',
        'requires_prescription' => 'boolean',
        'price'     => 'float',
        'old_price' => 'float',
        'width'     => 'float',
        'height'    => 'float',
        'depth'     => 'float',
    ];

    // ── Code name ───────────────────────────────────────────────────────────

    /**
     * Every product gets a generated code name built from its own information:
     *
     *     CATEGORY-BRAND-NAME-GENERIC-FORM   →   HEA-BIO-PARACE-PARA-TAB
     *
     * It is (re)generated automatically on save whenever the product has no
     * code name yet, or one of the fields it is built from changes. It is not
     * mass-assignable, so it can only come from this generator.
     */
    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            $inputsChanged = $product->isDirty(['name', 'generic_name', 'form', 'category_id', 'brand_id']);

            if (blank($product->code_name) || $inputsChanged) {
                $product->code_name = $product->generateCodeName();
            }
        });
    }

    /** Build the code name from this product's current information (unique across products). */
    public function generateCodeName(): string
    {
        $segment = fn (?string $value, int $length): string => substr(
            preg_replace('/[^A-Z0-9]/', '', strtoupper(Str::ascii((string) $value))),
            0,
            $length
        );

        $category = $this->category_id ? Category::whereKey($this->category_id)->value('name') : null;
        $brand    = $this->brand_id    ? Brand::whereKey($this->brand_id)->value('name')       : null;

        $base = collect([
            $segment($category, 3),
            $segment($brand, 3),
            $segment($this->name, 6),
            $segment($this->generic_name, 4),
            $segment($this->form, 3),
        ])->filter()->implode('-') ?: 'PRODUCT';

        // Two products with identical info get -2, -3 ... so the code stays unique.
        $candidate = $base;
        $n         = 1;

        while (static::where('code_name', $candidate)
                ->when($this->exists, fn ($q) => $q->whereKeyNot($this->getKey()))
                ->exists()) {
            $candidate = $base . '-' . (++$n);
        }

        return $candidate;
    }

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

    /** The stock/inventory entries recorded for this product. */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    /** Wishlist entries that reference this product. */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
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

    /**
     * Total sellable quantity across this product's active, unexpired stock
     * batches. This is what's actually available to order — not just the
     * raw sum of every stock row regardless of status.
     */
    public function availableStock(): int
    {
        return (int) $this->stocks()
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->where(function ($q) {
                $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now());
            })
            ->sum('quantity');
    }

    public function isInStock(): bool
    {
        return $this->availableStock() > 0;
    }
}