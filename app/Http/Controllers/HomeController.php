<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\FullWidthBanner;
use App\Models\NavItem;
use App\Models\PromoBanner;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // ── Site-wide ────────────────────────────────────────────────────────
        $settings = Setting::allAsArray();

        // ── Navigation (top-level items + their dropdown children) ───────────
        $navItems = NavItem::topLevel()->with('children')->get();

        // ── Hero slider ──────────────────────────────────────────────────────
        $sliders = Slider::active()->get();

        // ── Brands — ticker row ──────────────────────────────────────────────
        $tickerBrands = Brand::ticker()->get();

        // ── Shop by Category ─────────────────────────────────────────────────
        $categories = Category::active()->get();

        // ── Promo banners (2-column grid) ────────────────────────────────────
        $promoBanners = PromoBanner::active()->get();

        // ── Featured brands grid ─────────────────────────────────────────────
        $featuredBrands = Brand::featured()->get();

        // ── Full-width image banners ─────────────────────────────────────────
        $bannerOmron   = FullWidthBanner::forSection('omron');
        $bannerRitemed = FullWidthBanner::forSection('ritemed');

        // ── Product sections — each Section eager-loads its products,
        //    and each product eager-loads its category and brand ───────────────
        $productWith = ['products' => fn ($q) => $q->with(['category', 'brand'])];

        $hotDealsSection  = Section::byKey('hot_deals');
        $saleSection      = Section::byKey('sale');
        $promoPackSection = Section::byKey('promo_packs');
        $guardianSection  = Section::byKey('guardian');
        $featuredSection  = Section::byKey('featured');

        // Generics section split into two rows by sort_order
        $genericsSection = Section::byKey('generics');
        $genericsTop     = $genericsSection?->products()->with(['category', 'brand'])->where('sort_order', '<=', 4)->get();
        $genericsBottom  = $genericsSection?->products()->with(['category', 'brand'])->where('sort_order', '>', 4)->get();

        // Load products for all other sections (already filtered + ordered by Section model)
        foreach ([$hotDealsSection, $saleSection, $promoPackSection, $guardianSection, $featuredSection] as $sec) {
            $sec?->load($productWith);
        }

        // ── Blog posts — eager-load their category ───────────────────────────
        $blogs = Blog::active()->with('category')->limit(4)->get();

        return view('home', compact(
            'settings', 'navItems',
            'sliders',
            'tickerBrands', 'featuredBrands',
            'categories',
            'promoBanners',
            'bannerOmron', 'bannerRitemed',
            'hotDealsSection', 'saleSection', 'promoPackSection',
            'guardianSection',
            'genericsSection', 'genericsTop', 'genericsBottom',
            'featuredSection',
            'blogs'
        ));
    }
}
