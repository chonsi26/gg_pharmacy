<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    protected $fillable = ['user_id', 'product_id'];

    /** The customer who owns this wishlist entry. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** The wishlisted product. */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
