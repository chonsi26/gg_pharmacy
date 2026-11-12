<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Powers admin/reports.blade.php.
 *
 * Everything is computed from the live database:
 *  - Stocks Purchased (JSON key "purchases") = total NUMBER of stock records
 *                 (rows in `stocks`) of the product. With a period filter, only
 *                 records created (stocks.created_at) in the period are counted.
 *  - Total Products Orders (JSON key "unitsSold") = total NUMBER of orders containing the
 *                 product: every non-cancelled, non-deleted order (pending,
 *                 confirmed, ready, picked_up), dated by orders.created_at.
 *  - Total Purchases (JSON key "totalPurchases") = units customers purchased: the sum
 *                 of order_items.quantity of every non-cancelled, non-deleted order,
 *                 dated by orders.created_at.
 *  - Sales      = revenue (order_items.subtotal) of completed orders only
 *                 (status "picked_up"), dated by orders.picked_up_at
 *                 (falls back to updated_at).
 *  - Stock on hand (Total Stocks / Total Stock Quantity) = ALL stock batches of
 *                 the product (count / sum of stocks.quantity) right now; it is a
 *                 snapshot and is NOT affected by the period filter.
 */
class AdminReportsController extends Controller
{
    private const PERIODS = ['week', 'month', 'all'];

    // ── Page ─────────────────────────────────────────────────────────────────

    public function index(): View
    {
        $logo2    = Setting::get('logo2');
        $siteName = Setting::get('site_name', 'Pharmacy');

        return view('admin.reports', compact('logo2', 'siteName'));
    }

    // ── JSON data (?period=week|month|all) ───────────────────────────────────

    public function data(Request $request): JsonResponse
    {
        $period = $request->query('period', 'all');
        if (! in_array($period, self::PERIODS, true)) {
            $period = 'all';
        }

        $start = $this->periodStart($period);

        $products   = $this->productRows($start);
        $brands     = $this->groupRows($products, 'brand', 'No Brand', DB::table('brands')->pluck('name')->all());
        $categories = $this->groupRows($products, 'category', 'Uncategorized', DB::table('categories')->pluck('name')->all());

        $brandRows = $brands->map(fn ($g) => [
            'brand'         => $g['name'],
            'totalProducts' => $g['totalProducts'],
            'purchases'     => $g['purchases'],
            'totalPurchases' => $g['totalPurchases'],
            'mostSold'      => $g['mostSold'],
            'sales'         => $g['sales'],
        ])->sortByDesc('sales')->values();

        $categoryRows = $categories->map(fn ($g) => [
            'category'      => $g['name'],
            'totalProducts' => $g['totalProducts'],
            'totalBrands'   => $g['totalBrands'],
            'purchases'     => $g['purchases'],
            'totalPurchases' => $g['totalPurchases'],
            'sales'         => $g['sales'],
            'mostSold'      => $g['mostSold'],
        ])->sortByDesc('sales')->values();

        return response()->json([
            'period' => $period,
            'label'  => $this->periodLabel($period, $start),
            'totals' => [
                'products'  => $products->count(),
                'purchases' => $products->sum('purchases'),
                'totalPurchases' => $products->sum('totalPurchases'),
                'unitsSold' => $products->sum('unitsSold'),
                'sales'     => round($products->sum('sales'), 2),
            ],
            'products'   => $products->values(),
            'brands'     => $brandRows,
            'categories' => $categoryRows,
        ]);
    }

    // ── Aggregation ──────────────────────────────────────────────────────────

    /**
     * One row per product with stock-on-hand, purchases and sales figures.
     */
    private function productRows(?Carbon $start)
    {
        $stockStats     = $this->stockStatsByProduct();
        $purchaseStats  = $this->purchasesByProduct($start);
        $unitsSoldStats = $this->ordersByProduct($start);
        $totalPurchaseStats = $this->totalPurchasesByProduct($start);
        $salesStats     = $this->salesByProduct($start);

        return DB::table('products as p')
            ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
            ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
            ->orderBy('p.name')
            ->get(['p.id', 'p.name', 'c.name as category', 'b.name as brand'])
            ->map(function ($p) use ($stockStats, $purchaseStats, $unitsSoldStats, $totalPurchaseStats, $salesStats) {
                $stock = $stockStats[$p->id] ?? ['batches' => 0, 'onHand' => 0];
                $sale  = $salesStats[$p->id] ?? null;

                return [
                    'id'        => $p->id,
                    'name'      => $p->name,
                    'category'  => $p->category ?: 'Uncategorized',
                    'brand'     => $p->brand ?: 'No Brand',
                    'stocks'    => $stock['batches'],
                    'qty'       => $stock['onHand'],
                    'purchases' => (int) ($purchaseStats[$p->id] ?? 0),
                    'totalPurchases' => (int) ($totalPurchaseStats[$p->id] ?? 0),
                    'unitsSold' => (int) ($unitsSoldStats[$p->id] ?? 0),
                    'sales'     => round((float) ($sale->amount ?? 0), 2),
                ];
            });
    }

    /**
     * Per product: number of stock batches and units on hand (current snapshot).
     *
     * @return array<int, array{batches:int,onHand:int}>
     */
    private function stockStatsByProduct(): array
    {
        return DB::table('stocks')
            ->groupBy('product_id')
            ->selectRaw('product_id, COUNT(*) as batches, COALESCE(SUM(quantity), 0) as on_hand')
            ->get()
            ->mapWithKeys(fn ($s) => [$s->product_id => [
                'batches' => (int) $s->batches,
                'onHand'  => (int) $s->on_hand,
            ]])
            ->all();
    }

    /**
     * Stocks Purchased — per product: total number of stock records in the
     * 'stocks' table (limited to records created in the period when selected).
     */
    private function purchasesByProduct(?Carbon $start)
    {
        return DB::table('stocks')
            ->when($start, fn ($q) => $q->where('created_at', '>=', $start))
            ->groupBy('product_id')
            ->selectRaw('product_id, COUNT(*) as units')
            ->pluck('units', 'product_id');
    }

    /**
     * Total Products Orders — per product: total number of orders in the 'orders' table
     * that contain the product (non-cancelled, non-deleted) created in the period.
     */
    private function ordersByProduct(?Carbon $start)
    {
        return DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->whereNull('o.deleted_at')
            ->where('o.status', '!=', 'cancelled')
            ->when($start, fn ($q) => $q->where('o.created_at', '>=', $start))
            ->groupBy('oi.product_id')
            ->selectRaw('oi.product_id, COUNT(DISTINCT oi.order_id) as units')
            ->pluck('units', 'product_id');
    }

    /**
     * Total Purchases — per product: units purchased by customers, i.e. the sum of
     * order_items.quantity over the 'orders' + 'order_items' tables
     * (non-cancelled, non-deleted orders created in the period).
     */
    private function totalPurchasesByProduct(?Carbon $start)
    {
        return DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->whereNull('o.deleted_at')
            ->where('o.status', '!=', 'cancelled')
            ->when($start, fn ($q) => $q->where('o.created_at', '>=', $start))
            ->groupBy('oi.product_id')
            ->selectRaw('oi.product_id, SUM(oi.quantity) as units')
            ->pluck('units', 'product_id');
    }

    /**
     * Per product: revenue from completed (picked_up) orders.
     */
    private function salesByProduct(?Carbon $start)
    {
        return DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->whereNull('o.deleted_at')
            ->where('o.status', 'picked_up')
            ->when($start, fn ($q) => $q->whereRaw('COALESCE(o.picked_up_at, o.updated_at) >= ?', [$start]))
            ->groupBy('oi.product_id')
            ->selectRaw('oi.product_id, SUM(oi.quantity) as units, SUM(oi.subtotal) as amount')
            ->get()
            ->keyBy('product_id');
    }

    /**
     * Roll product rows up by brand or category. Empty brands/categories
     * (no products yet) are still listed with zeros.
     */
    private function groupRows($products, string $key, string $fallback, array $allNames)
    {
        $groups = $products->groupBy($key);

        foreach ($allNames as $name) {
            if (! $groups->has($name)) {
                $groups->put($name, collect());
            }
        }

        return $groups->map(function ($list, $name) {
            $top = $list->sortByDesc('unitsSold')->first();

            return [
                'name'          => $name,
                'totalProducts' => $list->count(),
                'totalBrands'   => $list->pluck('brand')->unique()->count(),
                'purchases'     => (int) $list->sum('purchases'),
                'totalPurchases' => (int) $list->sum('totalPurchases'),
                'sales'         => round($list->sum('sales'), 2),
                'mostSold'      => $top && $top['unitsSold'] > 0
                    ? "{$top['name']} (" . number_format($top['unitsSold']) . ($top['unitsSold'] === 1 ? ' order)' : ' orders)')
                    : '—',
            ];
        })->values();
    }

    // ── Dates ────────────────────────────────────────────────────────────────

    private function periodStart(string $period): ?Carbon
    {
        return match ($period) {
            'week'  => now()->startOfWeek(Carbon::MONDAY)->startOfDay(),
            'month' => now()->startOfMonth(),
            default => null,
        };
    }

    private function periodLabel(string $period, ?Carbon $start): string
    {
        if (! $start) {
            return 'All time';
        }

        return ($period === 'week' ? 'This week' : 'This month')
            . ' (since ' . $start->format('M j, Y') . ')';
    }
}