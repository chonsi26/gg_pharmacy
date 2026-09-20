<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    protected $fillable = [
        'key', 'label', 'description', 'see_all_url', 'heading_color', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    // ── Relationships ───────────────────────────────────────────────────────

    /** All products displayed in this section. */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class)->where('is_active', true)->orderBy('sort_order');
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /** Find a section by its slug key, e.g. Section::byKey('hot_deals'). */
    public static function byKey(string $key): ?self
    {
        return static::where('key', $key)->where('is_active', true)->first();
    }

    /**
     * Derive the fixed "section_N" key from a sort order, e.g. keyForSortOrder(3) -> 'section_3'.
     * Keys are never entered by hand — they always mirror the chosen sort order.
     */
    public static function keyForSortOrder(int $sortOrder): string
    {
        return 'section_' . $sortOrder;
    }
}