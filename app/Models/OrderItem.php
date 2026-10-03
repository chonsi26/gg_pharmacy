<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'stock_id', 'quantity',
        'unit_price', 'unit_old_price', 'subtotal',
        'prescription_file',
    ];

    protected $casts = [
        'quantity'       => 'integer',
        'unit_price'     => 'float',
        'unit_old_price' => 'float',
        'subtotal'       => 'float',
    ];

    // ── Relationships ───────────────────────────────────────────────────────

    /** The order this line item belongs to. */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** The product reserved in this line item (price snapshot lives on this row). */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /** The single stock batch this item's quantity was deducted from. */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /** Whether this line item's product requires a prescription upload. */
    public function requiresPrescription(): bool
    {
        return (bool) ($this->product?->requires_prescription);
    }

    /** Whether a prescription is required but hasn't been uploaded yet. */
    public function isMissingPrescription(): bool
    {
        return $this->requiresPrescription() && empty($this->prescription_file);
    }

    /** Build a line item from a Cart row, snapshotting the product's current price. */
    public static function fromCart(int $orderId, Cart $cart): self
    {
        return static::create([
            'order_id'       => $orderId,
            'product_id'     => $cart->product_id,
            'quantity'       => $cart->quantity,
            'unit_price'     => $cart->product->price,
            'unit_old_price' => $cart->product->old_price,
            'subtotal'       => round($cart->product->price * $cart->quantity, 2),
        ]);
    }
}