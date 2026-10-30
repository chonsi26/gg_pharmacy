<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Powers the staff header search bar (staffs/dashboard.blade.php and staffs/search.blade.php).
 *
 * Same behaviour as AdminSearchController, except staff can only search
 * PRODUCTS (there is no staff-directory search here).
 *
 *  - suggest(): JSON for the live dropdown that appears while typing.
 *    Each suggestion carries a "url" that deep-links to the staff inventory
 *    page with ?open={id}, which makes that page pop the information modal.
 *  - index():   the full search results page, reached when the person presses
 *    Enter (or "See all results") instead of picking a suggestion.
 *
 * Products match on name, generic name, form, category and brand (same fields
 * as the inventory screen's own search box).
 */
class StaffSearchController extends Controller
{
    private const SUGGEST_LIMIT = 5;
    private const PER_PAGE      = 24;

    // ── Live suggestions (JSON) ─────────────────────────────────────────────

    public function suggest(Request $request): JsonResponse
    {
        $q = $this->cleanQuery($request->query('q'));

        if ($q === '') {
            return response()->json([
                'query'       => '',
                'products'    => [],
                'results_url' => route('staff.search'),
            ]);
        }

        $products = $this->productQuery($q)
            ->limit(self::SUGGEST_LIMIT)
            ->get()
            ->map(fn (Product $p) => [
                'id'    => $p->id,
                'name'  => $p->name,
                'meta'  => $this->productMeta($p),
                'image' => $this->productImageUrl($p->image),
                'url'   => route('staff.inventory', ['open' => $p->id]),
            ])
            ->values();

        return response()->json([
            'query'       => $q,
            'products'    => $products,
            'results_url' => route('staff.search', ['q' => $q]),
        ]);
    }

    // ── Results page ────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $q = $this->cleanQuery($request->query('q'));

        $products      = collect();
        $productsTotal = 0;

        if ($q !== '') {
            $productBase   = $this->productQuery($q);
            $productsTotal = (clone $productBase)->reorder()->count();

            $products = $this->withAvailableQuantity($productBase)
                ->paginate(self::PER_PAGE)
                ->withQueryString();
        }

        return view('staffs.search', [
            'staff'         => Auth::guard('staff')->user(),
            'logo2'         => Setting::get('logo2'),
            'siteName'      => Setting::get('site_name', 'Pharmacy'),
            'q'             => $q,
            'products'      => $products,
            'productsTotal' => $productsTotal,
        ]);
    }

    // ── Query builder ───────────────────────────────────────────────────────

    private function productQuery(string $q): Builder
    {
        $escaped = $this->escapeLike($q);
        $like    = "%{$escaped}%";
        $starts  = "{$escaped}%";

        return Product::query()
            ->with(['category:id,name,icon_color,bg_color', 'brand:id,name'])
            ->where(function (Builder $sub) use ($like) {
                $sub->where('products.name', 'like', $like)
                    ->orWhere('products.generic_name', 'like', $like)
                    ->orWhere('products.form', 'like', $like)
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', $like))
                    ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', $like));
            })
            // Names that start with the search text come first, then generic names.
            ->orderByRaw(
                'CASE WHEN products.name LIKE ? THEN 0 WHEN products.generic_name LIKE ? THEN 1 ELSE 2 END',
                [$starts, $starts]
            )
            ->orderBy('products.name');
    }

    /**
     * Adds an `available_qty` attribute — the same "active, in-date, quantity > 0"
     * rule Product::availableStock() uses — in one query instead of one per card.
     */
    private function withAvailableQuantity(Builder $query): Builder
    {
        return $query->withSum(['stocks as available_qty' => function ($q) {
            $q->where('is_active', true)
              ->where('quantity', '>', 0)
              ->where(function ($e) {
                  $e->whereNull('expiry_date')->orWhere('expiry_date', '>=', now());
              });
        }], 'quantity');
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function cleanQuery($value): string
    {
        return Str::limit(trim((string) $value), 100, '');
    }

    /** Escape LIKE wildcards so typing "50%" or "a_b" searches for those characters literally. */
    private function escapeLike(string $value): string
    {
        return addcslashes($value, '\\%_');
    }

    private function productMeta(Product $p): string
    {
        return collect([$p->generic_name, $p->form, $p->brand->name ?? null])
            ->filter()
            ->implode(' · ') ?: ($p->category->name ?? 'Product');
    }

    /** Same resolution rules as AdminController::productImageUrl(). */
    private function productImageUrl(?string $image): ?string
    {
        if (! $image) {
            return null;
        }

        if (Str::startsWith($image, ['http://', 'https://'])) {
            return $image;
        }

        if (Str::startsWith($image, 'storage/')) {
            return asset($image);
        }

        return asset('storage/' . ltrim($image, '/'));
    }
}
