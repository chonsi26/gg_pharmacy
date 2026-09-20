<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FullWidthBanner extends Model
{
    /**
     * Maximum number of full-width banners allowed at any time.
     */
    public const MAX_BANNERS = 3;

    protected $fillable = ['section_key', 'sort_order', 'image', 'alt', 'is_active'];
    protected $casts    = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function forSection(string $key): ?self
    {
        return static::where('section_key', $key)
                     ->where('is_active', true)
                     ->first();
    }

    /**
     * The section_key is always derived from the sort_order, e.g. sort_order
     * 1 -> "fbanner_1". Keeping this in one place means the key can never
     * drift out of sync with the slot it represents.
     */
    public static function keyForSortOrder(int $sortOrder): string
    {
        return 'fbanner_' . $sortOrder;
    }

    /**
     * Automatically keep section_key in lockstep with sort_order whenever
     * sort_order is set (on create or update), and guarantee the table can
     * never silently hold more than MAX_BANNERS rows.
     */
    protected static function booted(): void
    {
        static::saving(function (self $banner) {
            if ($banner->sort_order !== null) {
                $banner->section_key = static::keyForSortOrder((int) $banner->sort_order);
            }
        });

        // The admin UI/controller already blocks a 4th banner from being
        // added, but this is a safety net for any other way a row could
        // land in the table (seeders, tinker, direct inserts via
        // Eloquent, future code paths, etc). Whenever a banner is
        // created, if that pushes the table past MAX_BANNERS rows, the
        // extra rows are deleted automatically — keeping the first
        // MAX_BANNERS slots (lowest sort_order first) and removing
        // anything beyond that, including their uploaded images.
        static::created(function (self $banner) {
            static::pruneToMaxBanners();
        });
    }

    /**
     * Delete any banners beyond the first MAX_BANNERS, ordered by
     * sort_order (then id as a tiebreaker). This is what keeps the table
     * capped at 3 rows no matter how a 4th row was inserted.
     */
    public static function pruneToMaxBanners(): void
    {
        $overflowIds = static::orderBy('sort_order')
            ->orderBy('id')
            ->pluck('id')
            ->slice(static::MAX_BANNERS);

        if ($overflowIds->isEmpty()) {
            return;
        }

        static::whereIn('id', $overflowIds)->get()->each(function (self $banner) {
            if ($banner->image && Str::startsWith($banner->image, 'storage/')) {
                Storage::disk('public')->delete(Str::after($banner->image, 'storage/'));
            }

            $banner->delete();
        });
    }
}