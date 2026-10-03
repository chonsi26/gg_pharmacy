<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'order_number', 'status',
        'subtotal', 'total', 'payment_method', 'proof_of_payment',
        'proof_of_refund',
        'note', 'prescription_file',
        'cancellation_reason',
        'confirmed_at', 'ready_at', 'picked_up_at', 'cancelled_at',
    ];

    protected $casts = [
        'subtotal'     => 'float',
        'total'        => 'float',
        'confirmed_at' => 'datetime',
        'ready_at'     => 'datetime',
        'picked_up_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // ── Relationships ───────────────────────────────────────────────────────

    /** The customer who placed this order. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** The line items reserved under this order. */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /** picked_up and cancelled are terminal — no further transitions from there. */
    public function isTerminal(): bool
    {
        return in_array($this->status, ['picked_up', 'cancelled'], true);
    }

    /** Whether the customer can still cancel this order themselves. */
    public function isCancellable(): bool
    {
        return ! $this->isTerminal();
    }

    /** Any order item on this order whose product needs a prescription upload. */
    public function hasMissingPrescriptions(): bool
    {
        return $this->items->contains(fn (OrderItem $item) => $item->isMissingPrescription());
    }

    /** Whether the customer chose to pay online (so a proof upload is allowed). */
    public function isOnlinePayment(): bool
    {
        return $this->payment_method === 'app';
    }

    /**
     * Proof of payment may only be uploaded for app payments, and only while
     * the order is confirmed (not while pending, and not once ready/closed).
     */
    public function canUploadProofOfPayment(): bool
    {
        return $this->isOnlinePayment() && $this->status === 'confirmed';
    }

    /** Whether a proof of payment file has been uploaded. */
    public function hasProofOfPayment(): bool
    {
        return ! empty($this->proof_of_payment);
    }

    /**
     * A refund is owed when the order was cancelled after the customer
     * already paid online (i.e. they uploaded a proof of payment).
     */
    public function needsRefund(): bool
    {
        return $this->status === 'cancelled'
            && $this->isOnlinePayment()
            && $this->hasProofOfPayment();
    }

    /** Refund proof can only be uploaded for cancelled orders that need a refund. */
    public function canUploadProofOfRefund(): bool
    {
        return $this->needsRefund();
    }

    /** Whether a proof of refund file has been uploaded. */
    public function hasProofOfRefund(): bool
    {
        return ! empty($this->proof_of_refund);
    }

    public function markConfirmed(): void
    {
        $this->update(['status' => 'confirmed', 'confirmed_at' => now()]);
    }

    public function markReady(): void
    {
        $this->update(['status' => 'ready', 'ready_at' => now()]);
    }

    public function markPickedUp(): void
    {
        $this->update(['status' => 'picked_up', 'picked_up_at' => now()]);
    }

    public function markCancelled(?string $reason = null): void
    {
        $this->update([
            'status'               => 'cancelled',
            'cancelled_at'         => now(),
            'cancellation_reason'  => $reason,
        ]);
    }

    /** Human-readable order number, e.g. ORD-20260926-00001. */
    public static function generateOrderNumber(): string
    {
        $todayCount = static::withTrashed()->whereDate('created_at', now())->count() + 1;

        return 'ORD-' . now()->format('Ymd') . '-' . str_pad((string) $todayCount, 5, '0', STR_PAD_LEFT);
    }
}