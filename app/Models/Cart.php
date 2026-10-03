<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // ── Relationships ───────────────────────────────────────────────────────

    /** The user this cart row belongs to. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** The product reserved in this cart row. */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    public function subtotal(): float
    {
        return round($this->product->price * $this->quantity, 2);
    }
}