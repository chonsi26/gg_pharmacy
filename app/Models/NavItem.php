<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NavItem extends Model
{
    protected $fillable = ['parent_id', 'label', 'href', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];

    // ── Relationships ───────────────────────────────────────────────────────

    /** The parent nav item (null for top-level items). */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(NavItem::class, 'parent_id');
    }

    /** Dropdown children of this nav item. */
    public function children(): HasMany
    {
        return $this->hasMany(NavItem::class, 'parent_id')->orderBy('sort_order');
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    /** Only top-level items (no parent), ordered. */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id')->orderBy('sort_order');
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    public function hasChildren(): bool
    {
        return $this->children->isNotEmpty();
    }
}
