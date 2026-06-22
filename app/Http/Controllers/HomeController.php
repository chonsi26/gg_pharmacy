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
use App\Models\Product; 
use Illuminate\Http\Request; 
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        // ── Site-wide ────────────────────────────────────────────────────────
        $settings = Setting::allAsArray();

        // ── Categories (Always loaded for the navigation bar) ────────────────
        $categories = Category::active()->orderBy('sort_order')->get();

        // ── Filter / Search Logic ────────────────────────────────────────────
        $searchQuery = $request->input('query');
        $categoryId = $request->input('category_id');
        
        $searchResults = null;
        $selectedCategory = null;

        if ($request->filled('query') || $request->filled('category_id')) {
            
            if ($request->filled('query')) {
                // Handle text search
                $searchResults = Product::active()
                    ->where(function ($q) use ($searchQuery) {
                        $q->where('name', 'LIKE', "%{$searchQuery}%")
                          ->orWhere('description', 'LIKE', "%{$searchQuery}%")
                          ->orWhere('ingredients', 'LIKE', "%{$searchQuery}%");
                    })
                    ->with(['category', 'brand'])
                    ->get();
            } elseif ($request->filled('category_id')) {
                // Handle navigation category filtering
                $selectedCategory = Category::find($categoryId);
                if ($selectedCategory) {
                    $searchResults = Product::active()
                        ->where('category_id', $categoryId)
                        ->with(['category', 'brand'])
                        ->get();
                }
            }

            // Initialize default homepage objects to prevent variable undefined errors in Blade
            $sliders          = collect();
            $tickerBrands     = collect();
            $promoBanners     = collect();
            $featuredBrands   = collect();
            $bannerOmron      = null;
            $bannerRitemed    = null;
            $hotDealsSection  = null;
            $saleSection      = null;
            $promoPackSection = null;
            $guardianSection  = null;
            $genericsSection  = null;
            $genericsTop      = collect();
            $genericsBottom   = collect();
            $featuredSection  = null;
            $blogs            = collect();
        } else {
            // ── Standard Homepage Data (Runs only when not searching/filtering) ──
            $sliders = Slider::active()->get();
            $tickerBrands = Brand::ticker()->get();
            $promoBanners = PromoBanner::active()->get();
            $featuredBrands = Brand::featured()->get();
            
            $bannerOmron   = FullWidthBanner::forSection('omron');
            $bannerRitemed = FullWidthBanner::forSection('ritemed');

            $productWith = ['products' => fn ($q) => $q->with(['category', 'brand'])];

            $hotDealsSection  = Section::byKey('hot_deals');
            $saleSection      = Section::byKey('sale');
            $promoPackSection = Section::byKey('promo_packs');
            $guardianSection  = Section::byKey('guardian');
            $featuredSection  = Section::byKey('featured');

            $genericsSection = Section::byKey('generics');
            $genericsTop     = $genericsSection?->products()->with(['category', 'brand'])->where('sort_order', '<=', 4)->get() ?? collect();
            $genericsBottom  = $genericsSection?->products()->with(['category', 'brand'])->where('sort_order', '>', 4)->get() ?? collect();

            foreach ([$hotDealsSection, $saleSection, $promoPackSection, $guardianSection, $featuredSection] as $sec) {
                $sec?->load($productWith);
            }

            $blogs = Blog::active()->with('category')->limit(4)->get();
        }

        return view('home', compact(
            'settings', 'categories', 'selectedCategory',
            'sliders',
            'tickerBrands', 'featuredBrands',
            'promoBanners',
            'bannerOmron', 'bannerRitemed',
            'hotDealsSection', 'saleSection', 'promoPackSection',
            'guardianSection',
            'genericsSection', 'genericsTop', 'genericsBottom',
            'featuredSection',
            'blogs',
            'searchResults', 
            'searchQuery'    
        ));
    }
}