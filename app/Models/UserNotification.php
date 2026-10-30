<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotification extends Model
{
    protected $table = 'user_notifications';

    protected $fillable = [
        'user_id', 'order_id', 'product_id',
        'type', 'title', 'message', 'url',
        'is_priority', 'resolved_at', 'expires_at',
        'is_read', 'read_at', 'event_at',
        'dedupe_key',
    ];

    protected $casts = [
        'is_priority' => 'boolean',
        'is_read'     => 'boolean',
        'resolved_at' => 'datetime',
        'expires_at'  => 'datetime',
        'read_at'     => 'datetime',
        'event_at'    => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────
    public function scopeForUser(Builder $q, int $userId): Builder
    {
        return $q->where('user_id', $userId);
    }

    /** Priority notifications whose action is still outstanding. */
    public function scopePinned(Builder $q): Builder
    {
        return $q->where('is_priority', true)->whereNull('resolved_at');
    }

    /** Pinned first (soonest deadline first), then newest event first. */
    public function scopeDisplayOrder(Builder $q): Builder
    {
        return $q->orderByRaw('CASE WHEN is_priority = 1 AND resolved_at IS NULL THEN 0 ELSE 1 END')
                 ->orderByRaw('CASE WHEN is_priority = 1 AND resolved_at IS NULL AND expires_at IS NOT NULL THEN 0 ELSE 1 END')
                 ->orderByRaw('CASE WHEN is_priority = 1 AND resolved_at IS NULL THEN expires_at END ASC')
                 ->orderByDesc('event_at')
                 ->orderByDesc('id');
    }

    /** What the red badge counts: unread items + anything still pinned. */
    public function scopeNeedsAttention(Builder $q): Builder
    {
        return $q->where(function ($w) {
            $w->where('is_read', false)
              ->orWhere(fn ($p) => $p->where('is_priority', true)->whereNull('resolved_at'));
        });
    }

    // ── Accessors ─────────────────────────────────────────────────────────
    public function getIsPinnedAttribute(): bool
    {
        return $this->is_priority && $this->resolved_at === null;
    }
}
