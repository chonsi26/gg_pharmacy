<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slider extends Model
{
    protected $fillable = ['product_id', 'image', 'alt', 'sort_order', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    /** The product this slide is about. */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}