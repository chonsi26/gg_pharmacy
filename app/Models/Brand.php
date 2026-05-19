<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = [
        'name', 'ticker_image', 'featured_image', 'featured_color',
        'show_in_ticker', 'show_in_featured', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'show_in_ticker'   => 'boolean',
        'show_in_featured' => 'boolean',
    ];

    // ── Relationships ───────────────────────────────────────────────────────

    /** Products that belong to this brand. */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /** Brands that appear in the top ticker row. */
    public function scopeTicker($query)
    {
        return $query->active()->where('show_in_ticker', true);
    }

    /** Brands that appear in the Featured Brands grid. */
    public function scopeFeatured($query)
    {
        return $query->active()->where('show_in_featured', true);
    }
}
