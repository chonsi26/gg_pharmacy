<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One row of the staff activity log (table: staff_logs).
 *
 * Staff counterpart of App\Models\Log (admin activity log).
 */
class StaffLog extends Model
{
    // ── Action keys (same keys as the admin Log, so the UI pills match) ──
    public const ACTION_CREATED    = 'created';
    public const ACTION_UPDATED    = 'updated';
    public const ACTION_DELETED    = 'deleted';
    public const ACTION_APPROVED   = 'approved';
    public const ACTION_READY      = 'ready';
    public const ACTION_COMPLETED  = 'completed';
    public const ACTION_CANCELLED  = 'cancelled';
    public const ACTION_REFUNDED   = 'refunded';
    public const ACTION_ACTIVATED  = 'activated';
    public const ACTION_LOGIN      = 'login';
    public const ACTION_LOGOUT     = 'logout';
    public const ACTION_REGISTERED = 'registered';
    public const ACTION_OTHER      = 'other';

    protected $table = 'staff_logs';

    protected $fillable = [
        'staff_id',
        'staff_name',
        'module',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'subject_label',
        'route_name',
        'method',
        'url',
        'ip_address',
        'user_agent',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    // ── Relations ────────────────────────────────────────────────────────

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    // ── Writing ──────────────────────────────────────────────────────────

    /**
     * Manually record an entry (the LogStaffActivity middleware uses this too), e.g.:
     *
     *   StaffLog::record(['staff_id' => 1, 'module' => 'Stocks', 'action' => 'other',
     *                     'description' => 'ran the nightly cleanup']);
     */
    public static function record(array $attributes): self
    {
        return static::create($attributes);
    }

    // ── Query scopes (used by StaffSettingsController@logs) ──────────────

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('created_at')->orderByDesc('id');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        $like = '%' . addcslashes($term, '%_\\') . '%';

        return $query->where(function (Builder $q) use ($like) {
            $q->where('description', 'like', $like)
                ->orWhere('staff_name', 'like', $like)
                ->orWhere('module', 'like', $like)
                ->orWhere('subject_label', 'like', $like);
        });
    }

    public function scopeOfModule(Builder $query, ?string $module): Builder
    {
        return ($module === null || $module === '' || $module === 'all')
            ? $query
            : $query->where('module', $module);
    }

    public function scopeOfAction(Builder $query, ?string $action): Builder
    {
        return ($action === null || $action === '' || $action === 'all')
            ? $query
            : $query->where('action', $action);
    }

    public function scopeCreatedBetween(Builder $query, ?string $from, ?string $to): Builder
    {
        return $query
            ->when($from, fn (Builder $q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn (Builder $q) => $q->whereDate('created_at', '<=', $to));
    }

    // ── Presentation helpers ─────────────────────────────────────────────

    /** "Today, 10:42 AM" · "Yesterday, 04:30 PM" · "Apr 21, 11:05 AM" · "Dec 3, 2025, 09:00 AM" */
    public function timeLabel(): string
    {
        $at = $this->created_at;

        if ($at->isToday()) {
            return 'Today, ' . $at->format('h:i A');
        }

        if ($at->isYesterday()) {
            return 'Yesterday, ' . $at->format('h:i A');
        }

        return $at->isCurrentYear()
            ? $at->format('M j, h:i A')
            : $at->format('M j, Y, h:i A');
    }
}
