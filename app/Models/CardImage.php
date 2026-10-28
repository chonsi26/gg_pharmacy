<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CardImage extends Model
{
    /**
     * Maximum number of card images allowed at any time. The homepage has
     * exactly two card-image tiles (one beside each dual-row product grid).
     */
    public const MAX_IMAGES = 2;

    protected $fillable = ['section_key', 'sort_order', 'image', 'alt', 'is_active'];
    protected $casts    = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * The active card image for a slot key, e.g. "card_image_1".
     */
    public static function forSection(string $key): ?self
    {
        return static::where('section_key', $key)
                     ->where('is_active', true)
                     ->first();
    }

    /**
     * The section_key is always derived from the sort_order, e.g. sort_order
     * 1 -> "card_image_1".
     */
    public static function keyForSortOrder(int $sortOrder): string
    {
        return 'card_image_' . $sortOrder;
    }

    protected static function booted(): void
    {
        // Keep section_key in lockstep with sort_order.
        static::saving(function (self $card) {
            if ($card->sort_order !== null) {
                $card->section_key = static::keyForSortOrder((int) $card->sort_order);
            }
        });

        // Safety net: whatever way a 3rd row lands in the table, anything
        // beyond MAX_IMAGES is removed automatically.
        static::created(function (self $card) {
            static::pruneToMaxImages();
        });
    }

    /**
     * Delete any card images beyond the first MAX_IMAGES (ordered by
     * sort_order, then id), including their uploaded files.
     */
    public static function pruneToMaxImages(): void
    {
        $overflowIds = static::orderBy('sort_order')
            ->orderBy('id')
            ->pluck('id')
            ->slice(static::MAX_IMAGES);

        if ($overflowIds->isEmpty()) {
            return;
        }

        static::whereIn('id', $overflowIds)->get()->each(function (self $card) {
            if ($card->image && Str::startsWith($card->image, 'storage/')) {
                Storage::disk('public')->delete(Str::after($card->image, 'storage/'));
            }

            $card->delete();
        });
    }
}
