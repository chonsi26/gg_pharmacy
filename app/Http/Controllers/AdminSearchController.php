<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Powers the admin header search bar (index.blade.php and search.blade.php).
 *
 *  - suggest(): JSON for the live dropdown that appears while typing.
 *    Each suggestion carries a "url" that deep-links to the product / staff
 *    page with ?open={id}, which makes that page pop the information modal.
 *  - index():   the full search results page, reached when the person presses
 *    Enter (or "See all results") instead of picking a suggestion.
 *
 * Products match on name, code name, generic name, form, category and brand (same fields
 * as the inventory screen's own search box). Staffs match on first/last/full
 * name, email, contact number and staff code (e.g. STF-0007).
 */
class AdminSearchController extends Controller
{
    private const SUGGEST_LIMIT = 5;
    private const PER_PAGE      = 24;
    private const ALL_TAB_LIMIT = 8;

    // ── Live suggestions (JSON) ─────────────────────────────────────────────

    public function suggest(Request $request): JsonResponse
    {
        $q = $this->cleanQuery($request->query('q'));

        if ($q === '') {
            return response()->json([
                'query'       => '',
                'products'    => [],
                'staffs'      => [],
                'results_url' => route('admin.search'),
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
                'url'   => route('admin.inventory', ['open' => $p->id]),
            ])
            ->values();

        $staffs = $this->staffQuery($q)
            ->limit(self::SUGGEST_LIMIT)
            ->get()
            ->map(fn (Staff $s) => [
                'id'       => $s->id,
                'name'     => trim("{$s->first_name} {$s->last_name}"),
                'meta'     => $this->staffCode($s) . ' · ' . $s->email,
                'image'    => $s->profile_picture ? asset($s->profile_picture) : null,
                'initials' => $this->initials($s),
                'tone'     => $s->id % 6 + 1,
                'url'      => route('admin.staffs', ['open' => $s->id]),
            ])
            ->values();

        return response()->json([
            'query'       => $q,
            'products'    => $products,
            'staffs'      => $staffs,
            'results_url' => route('admin.search', ['q' => $q]),
        ]);
    }

    // ── Results page ────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $logo2    = Setting::get('logo2');
        $siteName = Setting::get('site_name', 'Pharmacy');

        $q    = $this->cleanQuery($request->query('q'));
        $type = in_array($request->query('type'), ['products', 'staffs'], true)
            ? $request->query('type')
            : 'all';

        $products      = collect();
        $staffs        = collect();
        $productsTotal = 0;
        $staffsTotal   = 0;

        if ($q !== '') {
            $productBase = $this->productQuery($q);
            $staffBase   = $this->staffQuery($q);

            $productsTotal = (clone $productBase)->reorder()->count();
            $staffsTotal   = (clone $staffBase)->reorder()->count();

            if ($type === 'products') {
                $products = $this->withAvailableQuantity($productBase)
                    ->paginate(self::PER_PAGE)->withQueryString();
            } elseif ($type === 'staffs') {
                $staffs = $staffBase->paginate(self::PER_PAGE)->withQueryString();
            } else {
                $products = $this->withAvailableQuantity($productBase)->limit(self::ALL_TAB_LIMIT)->get();
                $staffs   = $staffBase->limit(self::ALL_TAB_LIMIT)->get();
            }
        }

        return view('admin.search', [
            'logo2'         => $logo2,
            'siteName'      => $siteName,
            'q'             => $q,
            'type'          => $type,
            'products'      => $products,
            'staffs'        => $staffs,
            'productsTotal' => $productsTotal,
            'staffsTotal'   => $staffsTotal,
        ]);
    }

    // ── Query builders ──────────────────────────────────────────────────────

    private function productQuery(string $q): Builder
    {
        $escaped = $this->escapeLike($q);
        $like    = "%{$escaped}%";
        $starts  = "{$escaped}%";

        return Product::query()
            ->with(['category:id,name,icon_color,bg_color', 'brand:id,name'])
            ->where(function (Builder $sub) use ($like) {
                $sub->where('products.name', 'like', $like)
                    ->orWhere('products.code_name', 'like', $like)
                    ->orWhere('products.generic_name', 'like', $like)
                    ->orWhere('products.form', 'like', $like)
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', $like))
                    ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', $like));
            })
            // Names that start with the search text come first, then generic names.
            ->orderByRaw(
                'CASE WHEN products.name LIKE ? THEN 0 WHEN products.code_name LIKE ? THEN 1 WHEN products.generic_name LIKE ? THEN 2 ELSE 3 END',
                [$starts, $starts, $starts]
            )
            ->orderBy('products.name');
    }

    private function staffQuery(string $q): Builder
    {
        $escaped = $this->escapeLike($q);
        $like    = "%{$escaped}%";
        $starts  = "{$escaped}%";

        // "STF-0007", "stf7" or "7" typed as a staff code → match that id.
        $codeId = preg_match('/^stf-?0*(\d+)$/i', $q, $m) ? (int) $m[1] : null;

        return Staff::query()
            ->where(function (Builder $sub) use ($like, $codeId) {
                $sub->where('first_name', 'like', $like)
                    ->orWhere('last_name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('contact_number', 'like', $like)
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$like]);

                if ($codeId !== null) {
                    $sub->orWhere('id', $codeId);
                }
            })
            ->orderByRaw(
                'CASE WHEN first_name LIKE ? THEN 0 WHEN last_name LIKE ? THEN 1 ELSE 2 END',
                [$starts, $starts]
            )
            ->orderBy('first_name')
            ->orderBy('last_name');
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
        return collect([$p->code_name, $p->generic_name, $p->form, $p->brand->name ?? null])
            ->filter()
            ->implode(' · ') ?: ($p->category->name ?? 'Product');
    }

    private function staffCode(Staff $s): string
    {
        return 'STF-' . str_pad($s->id, 4, '0', STR_PAD_LEFT);
    }

    private function initials(Staff $s): string
    {
        return strtoupper(mb_substr($s->first_name, 0, 1) . mb_substr($s->last_name, 0, 1)) ?: '?';
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