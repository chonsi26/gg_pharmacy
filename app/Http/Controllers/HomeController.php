<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\CardImage;
use App\Models\Category;
use App\Models\FullWidthBanner;
use App\Models\Product;
use App\Models\PromoBanner;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    /** Products shown per homepage section (unfiltered view only). */
    private const SECTION_LIMIT = 8;

    /** A brand "has many products" once it owns at least this many active products. */
    private const MANY_PRODUCTS_PER_BRAND = 5;

    /** "Cheap" means priced below this amount (PHP). */
    private const CHEAP_PRICE = 50;

    /**
     * Homepage section keys. Their titles / subtitles live in home.blade.php;
     * the product rules for each key live in sectionQuery() below.
     */
    private const SECTION_KEYS = [
        'hot_deals', 'sale', 'promo_packs', 'best_sellers',
        'new_products', 'generics', 'featured',
    ];

    public function index(Request $request): View
    {
        // ── Site-wide ────────────────────────────────────────────────────────
        $settings = Setting::allAsArray();

        // ── Categories (Always loaded for the navigation bar) ────────────────
        $categories = Category::active()->orderBy('sort_order')->get();

        // ── Filter / Search Logic ────────────────────────────────────────────
        $searchQuery = $request->input('query');
        $categoryId  = $request->input('category_id');
        $brandId     = $request->input('brand_id');

        // "See all products" for a homepage section, e.g. ?home_section=sale&seed=123
        $sectionKey = $request->input('home_section');
        if (! in_array($sectionKey, self::SECTION_KEYS, true)) {
            $sectionKey = null;
        }

        // The seed makes the "random" sections stable between the homepage and the
        // "See all products" page opened from it. A fresh page load gets a new seed.
        $seed = $request->integer('seed') ?: random_int(1, 999999);

        $searchResults    = null;
        $selectedCategory = null;
        $selectedSection  = null;   // a section key (string) — title is looked up in the blade
        $selectedBrand    = null;
        $sectionProducts  = [];

        if ($request->filled('query') || $request->filled('category_id') || $sectionKey || $request->filled('brand_id')) {

            if ($request->filled('query')) {
                // Handle text search
                $searchResults = Product::active()
                    ->where(function ($q) use ($searchQuery) {
                        $q->where('name', 'LIKE', "%{$searchQuery}%")
                          ->orWhere('description', 'LIKE', "%{$searchQuery}%")
                          ->orWhere('ingredients', 'LIKE', "%{$searchQuery}%")
                          ->orWhere('code_name', 'LIKE', "%{$searchQuery}%");
                    })
                    ->with(['category', 'brand'])->withAvailableStock()
                    ->get();
            } elseif ($request->filled('category_id')) {
                // Handle navigation category filtering
                $selectedCategory = Category::find($categoryId);
                if ($selectedCategory) {
                    $searchResults = Product::active()
                        ->where('category_id', $categoryId)
                        ->with(['category', 'brand'])->withAvailableStock()
                        ->get();
                }
            } elseif ($sectionKey) {
                // "See all products" for a homepage section: every product, with the
                // section's own products first (the homepage's 8 are the first 8).
                $selectedSection = $sectionKey;
                $searchResults   = $this->allProductsForSection($sectionKey, $seed);
            } elseif ($request->filled('brand_id')) {
                // Handle "shop by brand" clicks (ticker & featured brand logos)
                $selectedBrand = Brand::find($brandId);
                if ($selectedBrand) {
                    $searchResults = Product::active()
                        ->where('brand_id', $brandId)
                        ->with(['category', 'brand'])->withAvailableStock()
                        ->orderBy('sort_order')
                        ->get();
                }
            }

            // Initialize default homepage objects to prevent undefined variable errors in Blade
            $sliders        = collect();
            $tickerBrands   = collect();
            $promoBanners   = collect();
            $featuredBrands = collect();
            $fbanner_1      = null;
            $fbanner_2      = null;
            $fbanner_3      = null;
            $cardImage_1    = null;
            $cardImage_2    = null;
            $blogs          = collect();
        } else {
            // ── Standard Homepage Data (Runs only when not searching/filtering) ──
            $sliders        = Slider::active()->get();
            $tickerBrands   = Brand::ticker()->get();
            $promoBanners   = PromoBanner::active()->get();
            $featuredBrands = Brand::featured()->get();

            $fbanner_1 = FullWidthBanner::forSection('fbanner_1');
            $fbanner_2 = FullWidthBanner::forSection('fbanner_2');
            $fbanner_3 = FullWidthBanner::forSection('fbanner_3');

            $cardImage_1 = CardImage::forSection('card_image_1');
            $cardImage_2 = CardImage::forSection('card_image_2');

            // Product-driven sections (no sections table). Each is limited to
            // SECTION_LIMIT in-stock products.
            foreach (self::SECTION_KEYS as $key) {
                $sectionProducts[$key] = $this->homeSectionProducts($key, $seed);
            }

            $blogs = Blog::active()->with('category')->limit(4)->get();
        }

        return view('home', compact(
            'settings', 'categories', 'selectedCategory', 'selectedSection', 'selectedBrand',
            'sliders',
            'tickerBrands', 'featuredBrands',
            'promoBanners',
            'fbanner_1', 'fbanner_2', 'fbanner_3',
            'cardImage_1', 'cardImage_2',
            'sectionProducts', 'seed',
            'blogs',
            'searchResults',
            'searchQuery'
        ));
    }

    // ════════════════════════════════════════════════════════════════════════
    //  Section helpers
    // ════════════════════════════════════════════════════════════════════════

    /**
     * Rules for every section: which active products belong to it, ranked.
     * Returns null for "featured", which is always random.
     *
     * The query exposes `sold_qty` (units sold) and `brand_products` (how many
     * active products the product's brand has) so the rules can use them.
     */
    private function sectionQuery(string $key): ?Builder
    {
        $monthStart = now()->startOfMonth();

        // Units sold per product (cancelled / deleted orders don't count).
        $sales = fn (?\DateTimeInterface $since = null) => DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereNull('orders.deleted_at')
            ->where('orders.status', '!=', 'cancelled')
            ->when($since, fn ($q) => $q->where('orders.created_at', '>=', $since))
            ->groupBy('order_items.product_id')
            ->selectRaw('order_items.product_id, SUM(order_items.quantity) AS sold_qty');

        $base = fn (?\DateTimeInterface $salesSince = null) => Product::query()
            ->active()
            ->select('products.*')
            ->selectRaw('COALESCE(sales.sold_qty, 0) AS sold_qty')
            ->leftJoinSub($sales($salesSince), 'sales', 'sales.product_id', '=', 'products.id');

        switch ($key) {
            // New this month, from brands that have many products. Biggest brand first, then newest.
            case 'hot_deals':
                $brandCounts = DB::table('products')
                    ->where('is_active', true)
                    ->whereNotNull('brand_id')
                    ->groupBy('brand_id')
                    ->selectRaw('brand_id, COUNT(*) AS brand_products');

                return $base()
                    ->selectRaw('bc.brand_products AS brand_products')
                    ->joinSub($brandCounts, 'bc', 'bc.brand_id', '=', 'products.brand_id')
                    ->where('bc.brand_products', '>=', self::MANY_PRODUCTS_PER_BRAND)
                    ->where('products.created_at', '>=', $monthStart)
                    ->orderByDesc('bc.brand_products')
                    ->orderByDesc('products.created_at');

            // Cheap products (under ₱50). Lowest price first, then best sellers.
            case 'sale':
                return $base()
                    ->where('products.price', '<', self::CHEAP_PRICE)
                    ->orderBy('products.price')
                    ->orderByDesc('sold_qty');

            // Names containing "promo". Most sold first.
            case 'promo_packs':
                return $base()
                    ->where('products.name', 'LIKE', '%promo%')
                    ->orderByDesc('sold_qty')
                    ->orderByDesc('products.created_at');

            // Sold this month. Most sold first. (Nothing sold → empty → random fallback.)
            case 'best_sellers':
                return $base($monthStart)
                    ->whereNotNull('sales.sold_qty')
                    ->orderByDesc('sold_qty')
                    ->orderByDesc('products.created_at');

            // Newest products first.
            case 'new_products':
                return $base()
                    ->orderByDesc('products.created_at')
                    ->orderByDesc('products.id');

            // Names containing "generic" / "generics". Most sold first.
            case 'generics':
                return $base()
                    ->where('products.name', 'LIKE', '%generic%')
                    ->orderByDesc('sold_qty')
                    ->orderByDesc('products.created_at');

            default: // featured
                return null;
        }
    }

    /**
     * Products for one homepage section: in-stock only, capped at SECTION_LIMIT.
     * Products that match the section's rule come first (ranked). If fewer than
     * SECTION_LIMIT match, the remaining slots are filled with random in-stock
     * products (never repeating a matched one).
     */
    private function homeSectionProducts(string $key, int $seed): Collection
    {
        $query = $this->sectionQuery($key);

        $products = $query
            ? $query
                ->hasAvailableStock()
                ->with(['category', 'brand'])
                ->withAvailableStock()
                ->limit(self::SECTION_LIMIT)
                ->get()
            : new Collection();

        $missing = self::SECTION_LIMIT - $products->count();

        if ($missing > 0) {
            $products = $products->concat(
                $this->randomProducts($seed, $missing, $products->pluck('id')->all())
            )->values();
        }

        return $products;
    }

    /**
     * "See all products" for a section: every active product, with the
     * section's own products first (Sale is the exception: all products by price, lowest first). The first SECTION_LIMIT entries are exactly
     * the products shown on the homepage for that section.
     */
    private function allProductsForSection(string $key, int $seed): Collection
    {
        // Sale: the whole list runs strictly from the lowest price to the highest.
        if ($key === 'sale') {
            return Product::active()
                ->with(['category', 'brand'])
                ->withAvailableStock()
                ->orderBy('price')
                ->orderBy('id')
                ->get();
        }

        $query = $this->sectionQuery($key);

        // 1. exactly what the homepage shows
        $ids = $this->homeSectionProducts($key, $seed)->pluck('id');

        // 2. the rest of the section's matches (including out-of-stock ones)
        if ($query) {
            $ids = $ids->merge($query->pluck('products.id'));
        }

        // 3. everything else
        $ids = $ids
            ->merge($this->shuffledIds($seed))
            ->unique()
            ->values();

        $position = $ids->flip();

        return Product::query()
            ->whereIn('id', $ids)
            ->with(['category', 'brand'])
            ->withAvailableStock()
            ->get()
            ->sortBy(fn (Product $p) => $position[$p->id])
            ->values();
    }

    /** Random in-stock products (skipping $excludeIds); the same seed always gives the same picks. */
    private function randomProducts(int $seed, int $limit, array $excludeIds = []): Collection
    {
        $ids = $this->shuffledIds($seed, inStockOnly: true)
            ->reject(fn ($id) => in_array($id, $excludeIds, true))
            ->take($limit)
            ->values();
        $position = $ids->flip();

        return Product::query()
            ->whereIn('id', $ids)
            ->with(['category', 'brand'])
            ->withAvailableStock()
            ->get()
            ->sortBy(fn (Product $p) => $position[$p->id])
            ->values();
    }

    /** Active product ids in a seed-based pseudo-random (but repeatable) order. */
    private function shuffledIds(int $seed, bool $inStockOnly = false)
    {
        return Product::active()
            ->when($inStockOnly, fn ($q) => $q->hasAvailableStock())
            ->pluck('id')
            ->sortBy(fn ($id) => crc32($seed . '-' . $id))
            ->values();
    }
}