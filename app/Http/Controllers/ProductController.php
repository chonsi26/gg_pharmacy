<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        // Eager-load relationships needed on the detail page
        $product->load(['category', 'brand', 'section']);

        // ── Site-wide ────────────────────────────────────────────────────────
        $settings = Setting::allAsArray();

        // ── Navigation (categories, same as the homepage nav) ─────────────────
        $categories = Category::active()->orderBy('sort_order')->get();

        // ── Related products (same brand, excluding current, max 10) ──────────
        // Products without a brand have nothing to match on, so skip the query.
        $relatedProducts = $product->brand_id
            ? Product::active()
                ->where('brand_id', $product->brand_id)
                ->where('id', '!=', $product->id)
                ->with(['category', 'brand'])
                ->withAvailableStock()
                ->orderBy('sort_order')
                ->limit(10)
                ->get()
            : collect();

        // ── Wishlist state (drives whether the heart renders red) ─────────────
        $inWishlist = Auth::check()
            && Auth::user()->wishlists()->where('product_id', $product->id)->exists();

        // ── Availability (active, unexpired stock only) ───────────────────────
        $availableStock = $product->availableStock();
        $inStock        = $availableStock > 0;

        return view('product', compact('product', 'settings', 'categories', 'relatedProducts', 'inWishlist', 'inStock', 'availableStock'));
    }
}