<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PromoBanner extends Model
{
    /**
     * Promo banners are limited to two slots (sort_order 1 and 2), matching
     * the two-tile layout reserved for them on the homepage sidebar.
     */
    public const MAX_BANNERS = 2;

    protected $fillable = ['image', 'alt', 'type', 'sort_order', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Self-healing guard: every time a promo banner row is created, make sure
     * the table never actually holds more than MAX_BANNERS rows. This is a
     * safety net on top of the controller's own limit check — it protects
     * against seeders, imports, direct DB inserts, or race conditions ever
     * leaving a 3rd (or later) row sitting in the table.
     */
    protected static function booted(): void
    {
        static::created(function () {
            static::pruneExcess();
        });
    }

    /**
     * Keep only the oldest MAX_BANNERS rows (by id). Anything from the
     * 3rd row onward is deleted, along with its stored image file so we
     * don't leave orphaned uploads behind.
     */
    public static function pruneExcess(): void
    {
        $keepIds = static::orderBy('id')->limit(self::MAX_BANNERS)->pluck('id');

        static::whereNotIn('id', $keepIds)
            ->get()
            ->each(function (self $banner) {
                if ($banner->image && Str::startsWith($banner->image, 'storage/')) {
                    Storage::disk('public')->delete(Str::after($banner->image, 'storage/'));
                }

                $banner->delete();
            });
    }
}