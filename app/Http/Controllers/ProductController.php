<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
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

        // ── Related products (same section, excluding current, max 10) ────────
        $relatedProducts = Product::active()
            ->where('section_id', $product->section_id)
            ->where('id', '!=', $product->id)
            ->with(['category', 'brand'])
            ->orderBy('sort_order')
            ->limit(10)
            ->get();

        return view('product', compact('product', 'settings', 'categories', 'relatedProducts'));
    }
}