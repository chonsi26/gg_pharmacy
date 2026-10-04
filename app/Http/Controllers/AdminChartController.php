<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * JSON endpoints that feed the admin dashboard widgets:
 *   - Weekly Sales Overview   → weeklySales()
 *   - Order Status (donut)    → orderStatus()
 *   - Revenue by Product      → revenueByProduct()
 *   - Recent Transactions     → recentTransactions()
 *
 * Definitions used throughout
 * ───────────────────────────
 *  • A "sale" is an order with status `picked_up`, dated by `picked_up_at`
 *    (money is only counted once the customer has actually collected it).
 *  • A "refund" is a cancelled order that was paid online (`app`) and has a
 *    proof of payment — i.e. Order::needsRefund().
 *  • Order Status groups: Completed = picked_up,
 *    Pending = pending + confirmed + ready (still open), Cancelled = cancelled.
 *  • Soft-deleted orders are excluded automatically by the SoftDeletes trait.
 */
class AdminChartController extends Controller
{
    // ── Weekly Sales Overview ───────────────────────────────────────────────

    /**
     * GET /admin/charts/sales?range=week|month
     *
     * One point per day. Days that haven't happened yet return `val: null`
     * so the front end can stop the line at today instead of dropping to 0.
     */
    public function weeklySales(Request $request): JsonResponse
    {
        $range = $request->query('range') === 'month' ? 'month' : 'week';
        $now   = now();

        if ($range === 'month') {
            $start = $now->copy()->startOfMonth();
            $end   = $now->copy()->endOfMonth();
        } else {
            $start = $now->copy()->startOfWeek(Carbon::MONDAY);
            $end   = $now->copy()->endOfWeek(Carbon::SUNDAY);
        }

        // Group in PHP so this works on any DB driver (no DATE() differences).
        $perDay = Order::query()
            ->where('status', 'picked_up')
            ->whereBetween('picked_up_at', [$start, $end])
            ->get(['id', 'total', 'picked_up_at'])
            ->groupBy(fn (Order $o) => $o->picked_up_at->toDateString())
            ->map(fn ($orders) => round($orders->sum('total'), 2));

        $todayKey = $now->toDateString();
        $days     = [];
        $total    = 0.0;

        foreach (CarbonPeriod::create($start->copy()->startOfDay(), $end->copy()->startOfDay()) as $date) {
            $key      = $date->toDateString();
            $isFuture = $key > $todayKey;
            $val      = $isFuture ? null : (float) ($perDay[$key] ?? 0);

            $total += $val ?? 0;

            $days[] = [
                'date'  => $key,
                'label' => $range === 'month' ? $date->format('j') : $date->format('D'),
                'val'   => $val,
                'today' => $key === $todayKey,
            ];
        }

        return response()->json([
            'range' => $range,
            'days'  => $days,
            'total' => round($total, 2),
        ]);
    }

    // ── Order Status (donut) ────────────────────────────────────────────────

    /**
     * GET /admin/charts/order-status
     *
     * Orders placed this month, bucketed into Completed / Pending / Cancelled.
     */
    public function orderStatus(): JsonResponse
    {
        $counts = Order::query()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->select('status', DB::raw('COUNT(*) as aggregate'))
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $completed = (int) ($counts['picked_up'] ?? 0);
        $cancelled = (int) ($counts['cancelled'] ?? 0);
        $pending   = (int) (($counts['pending'] ?? 0) + ($counts['confirmed'] ?? 0) + ($counts['ready'] ?? 0));
        $total     = $completed + $pending + $cancelled;

        $segment = fn (int $count) => [
            'count'    => $count,
            'fraction' => $total > 0 ? $count / $total : 0,
            'percent'  => $total > 0 ? (int) round($count / $total * 100) : 0,
        ];

        return response()->json([
            'total'     => $total,
            'completed' => $segment($completed),
            'pending'   => $segment($pending),
            'cancelled' => $segment($cancelled),
        ]);
    }

    // ── Revenue by Product ──────────────────────────────────────────────────

    /**
     * GET /admin/charts/revenue-by-product
     *
     * Top 5 products by revenue from orders picked up this month, using the
     * price snapshot stored on each order item (not the product's current price).
     */
    public function revenueByProduct(): JsonResponse
    {
        $range = [now()->startOfMonth(), now()->endOfMonth()];

        $base = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereNull('orders.deleted_at')
            ->where('orders.status', 'picked_up')
            ->whereBetween('orders.picked_up_at', $range);

        $products = (clone $base)
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->select('products.id', 'products.name', DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get()
            ->map(fn ($row) => [
                'name'    => $row->name,
                'short'   => mb_strimwidth($row->name, 0, 14, '…'),
                'revenue' => round((float) $row->revenue, 2),
            ])
            ->values();

        return response()->json([
            'products'      => $products,
            // Revenue across ALL products this month, not just the top 5 shown.
            'total_revenue' => round((float) (clone $base)->sum('order_items.subtotal'), 2),
        ]);
    }

    // ── Recent Transactions ─────────────────────────────────────────────────

    /**
     * GET /admin/charts/recent-transactions
     *
     * Latest 5 money movements: completed sales (money in) and online-payment
     * refunds for cancelled orders (money out), newest first.
     */
    public function recentTransactions(): JsonResponse
    {
        $withRelations = fn ($query) => $query
            ->with(['user' => fn ($q) => $q->withTrashed()])
            ->withSum('items', 'quantity');

        $sales = $withRelations(Order::query())
            ->where('status', 'picked_up')
            ->whereNotNull('picked_up_at')
            ->orderByDesc('picked_up_at')
            ->limit(5)
            ->get()
            ->map(fn (Order $o) => $this->transaction($o, 'sale', $o->picked_up_at));

        $refunds = $withRelations(Order::query())
            ->where('status', 'cancelled')
            ->where('payment_method', 'app')
            ->whereNotNull('proof_of_payment')
            ->whereNotNull('cancelled_at')
            ->orderByDesc('cancelled_at')
            ->limit(5)
            ->get()
            ->map(fn (Order $o) => $this->transaction($o, 'refund', $o->cancelled_at));

        $transactions = $sales->concat($refunds)
            ->sortByDesc('sort_key')
            ->take(5)
            ->map(fn (array $t) => collect($t)->except('sort_key')->all())
            ->values();

        return response()->json(['transactions' => $transactions]);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function transaction(Order $order, string $type, Carbon $at): array
    {
        $isSale   = $type === 'sale';
        $customer = $order->user?->full_name ?: 'Customer';
        $items    = (int) ($order->items_sum_quantity ?? 0);

        return [
            'type'     => $type,
            'title'    => ($isSale ? 'Sale ' : 'Refund ') . $order->order_number . ' — ' . $customer,
            'subtitle' => $this->friendlyTime($at) . ' · ' . $items . ' ' . ($items === 1 ? 'item' : 'items'),
            'amount'   => ($isSale ? '+' : '-') . '₱' . $this->money($order->total),
            'sort_key' => $at->timestamp,
        ];
    }

    /** "Today, 9:14 AM" / "Yesterday, 4:30 PM" / "Sep 26, 2:12 PM" */
    private function friendlyTime(Carbon $at): string
    {
        $time = $at->format('g:i A');

        if ($at->isToday()) {
            return "Today, {$time}";
        }
        if ($at->isYesterday()) {
            return "Yesterday, {$time}";
        }

        return $at->format('M j') . ", {$time}";
    }

    /** Whole pesos without decimals ("245"), otherwise two decimals ("245.50"). */
    private function money(float $amount): string
    {
        return number_format($amount, floor($amount) == $amount ? 0 : 2);
    }
}
