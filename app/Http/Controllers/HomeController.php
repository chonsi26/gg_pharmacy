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
        $sectionId = $request->input('section_id');
        $brandId = $request->input('brand_id');

        $searchResults = null;
        $selectedCategory = null;
        $selectedSection = null;
        $selectedBrand = null;

        if ($request->filled('query') || $request->filled('category_id') || $request->filled('section_id') || $request->filled('brand_id')) {
            
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
            } elseif ($request->filled('section_id')) {
                // Handle "See all products" for a chosen section
                $selectedSection = Section::find($sectionId);
                if ($selectedSection) {
                    $searchResults = Product::active()
                        ->where('section_id', $sectionId)
                        ->with(['category', 'brand'])
                        ->orderBy('sort_order')
                        ->get();
                }
            } elseif ($request->filled('brand_id')) {
                // Handle "shop by brand" clicks (ticker & featured brand logos)
                $selectedBrand = Brand::find($brandId);
                if ($selectedBrand) {
                    $searchResults = Product::active()
                        ->where('brand_id', $brandId)
                        ->with(['category', 'brand'])
                        ->orderBy('sort_order')
                        ->get();
                }
            }

            // Initialize default homepage objects to prevent variable undefined errors in Blade
            $sliders          = collect();
            $tickerBrands     = collect();
            $promoBanners     = collect();
            $featuredBrands   = collect();
            $fbanner_1      = null;
            $fbanner_2    = null;
            $section1  = null;
            $section2      = null;
            $section3 = null;
            $section4 = null;
            $section5  = null;
            $section5_top      = collect();
            $section5_bottom   = collect();
            $section6  = null;
            $section6_top      = collect();
            $section6_bottom   = collect();
            $section7  = null;
            $extraSections    = collect();
            $blogs            = collect();
        } else {
            // ── Standard Homepage Data (Runs only when not searching/filtering) ──
            $sliders = Slider::active()->get();
            $tickerBrands = Brand::ticker()->get();
            $promoBanners = PromoBanner::active()->get();
            $featuredBrands = Brand::featured()->get();
            
            $fbanner_1   = FullWidthBanner::forSection('fbanner_1');
            $fbanner_2 = FullWidthBanner::forSection('fbanner_2');

            $productWith = ['products' => fn ($q) => $q->with(['category', 'brand'])];

            $section1  = Section::byKey('section_1');
            $section2      = Section::byKey('section_2');
            $section3 = Section::byKey('section_3');
            $section4 = Section::byKey('section_4');
            $section5  = Section::byKey('section_5');
            $section5_top     = $section5?->products()->with(['category', 'brand'])->where('sort_order', '<=', 4)->get() ?? collect();
            $section5_bottom  = $section5?->products()->with(['category', 'brand'])->where('sort_order', '>', 4)->get() ?? collect();
            $section6  = Section::byKey('section_6');

            $section7 = Section::byKey('section_7');
            $section6_top     = $section6?->products()->with(['category', 'brand'])->where('sort_order', '<=', 4)->get() ?? collect();
            $section6_bottom  = $section6?->products()->with(['category', 'brand'])->where('sort_order', '>', 4)->get() ?? collect();

            foreach ([$section1, $section2, $section3, $section4, $section5, $section6, $section7] as $sec) {
                $sec?->load($productWith);
            }

            // Any further sections (sort_order > 7) render dynamically as simple
            // carousels — new sections added via the admin panel show up here
            // automatically without any code changes.
            $extraSections = Section::active()
                ->where('sort_order', '>', 7)
                ->get();
            $extraSections->each(fn ($sec) => $sec->load($productWith));

            $blogs = Blog::active()->with('category')->limit(4)->get();
        }

        return view('home', compact(
            'settings', 'categories', 'selectedCategory', 'selectedSection', 'selectedBrand',
            'sliders',
            'tickerBrands', 'featuredBrands',
            'promoBanners',
            'fbanner_1', 'fbanner_2',
            'section1', 'section2', 'section3',
            'section4',
            'section5', 'section5_top', 'section5_bottom',
            'section6', 'section6_top', 'section6_bottom',
            'section7',
            'extraSections',
            'blogs',
            'searchResults', 
            'searchQuery'    
        ));
    }
}