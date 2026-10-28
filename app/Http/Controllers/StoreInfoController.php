<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Media;
use App\Models\Setting;
use Illuminate\View\View;

class StoreInfoController extends Controller
{
    /**
     * Public "Store Info" page: pharmacy details from the `settings` table
     * plus the photos and videos from the `media` table.
     */
    public function index(): View
    {
        // Same key/value array the home page uses (earliest row wins on duplicates).
        $settings = Setting::allAsArray();

        // The shared header's navigation bar lists the active categories.
        $categories = Category::active()->orderBy('sort_order')->get();

        // Only active items, already ordered by sort_order (see Media::scopeActive).
        $photos = Media::active()->photos()->get();
        $videos = Media::active()->videos()->get();

        // Validated https Google Maps embed URL, or null if missing/invalid.
        $mapEmbedUrl = Setting::mapEmbedUrl();

        return view('store_info', compact('settings', 'categories', 'photos', 'videos', 'mapEmbedUrl'));
    }
}