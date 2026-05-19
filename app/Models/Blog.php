<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    protected $fillable = [
        'title', 'excerpt', 'image',
        'icon_class', 'icon_color', 'icon_bg',
        'day', 'month', 'comment_count',
        'category_id', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    // ── Relationships ───────────────────────────────────────────────────────

    /** The category this blog post is filed under. */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->latest();
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    public function commentLabel(): string
    {
        return $this->comment_count > 0
            ? $this->comment_count . ' COMMENT' . ($this->comment_count > 1 ? 'S' : '')
            : 'NO COMMENTS';
    }

    public function hasImage(): bool   { return !empty($this->image); }
    public function hasIcon(): bool    { return !empty($this->icon_class); }
}
