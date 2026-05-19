<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'icon_class', 'icon_color', 'bg_color', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    // ── Relationships ───────────────────────────────────────────────────────

    /** All products that belong to this category. */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /** All blog posts tagged with this category. */
    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
