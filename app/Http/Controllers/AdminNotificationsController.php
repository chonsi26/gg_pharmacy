<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Admin notification bell.
 *
 * Notifications are DERIVED from live data (stocks, orders, customers) every
 * time the bell is loaded, then stored in the `notifications` table so they
 * keep a read/unread state and a timestamp.
 *
 *  ┌ IMPORTANT (priority 1-3) ───────────────────────────────────────────┐
 *  │ Pinned at the top of the feed until the underlying problem is       │
 *  │ handled. Marking one as "read" does NOT unpin it — only fixing the  │
 *  │ cause (restock, finish the order, send the refund…) does, at which  │
 *  │ point it is resolved automatically and drops out of the feed.       │
 *  └──────────────────────────────────────────────────────────────────────┘
 *  ┌ INFORMATIONAL (priority 0) ─────────────────────────────────────────┐
 *  │ Things that happened (order completed/cancelled, payment proof,     │
 *  │ prescription uploaded, new customer). Sit below the pinned ones,    │
 *  │ newest first, and can be marked read.                               │
 *  └──────────────────────────────────────────────────────────────────────┘
 */
class AdminNotificationsController extends Controller
{
    // ── Tunables ──────────────────────────────────────────────────────────
    /** A product is "low stock" when its sellable quantity is 1..N. Keep in sync with the dashboard's $lowStockItems. */
    private const LOW_STOCK_THRESHOLD = 10;

    /** A batch is "expiring" when it expires within N days (dashboard card says "≤ 10 days"). */
    private const EXPIRING_DAYS = 10;

    /** Informational events older than this are not turned into notifications. */
    private const INFO_WINDOW_DAYS = 7;

    /** Max informational notifications generated per type per sync (stops a flood on first run). */
    private const PER_TYPE_CAP = 25;

    /** Handled/old notifications are deleted after this many days. */
    private const PRUNE_AFTER_DAYS = 30;

    private const DEFAULT_LIMIT = 15;
    private const MAX_LIMIT     = 100;

    /** Orders that are still "ongoing / unfinished". */
    private const OPEN_STATUSES = ['pending', 'confirmed', 'ready'];

    /**
     * type => priority + tone (tone drives the icon colour in the dropdown).
     * Unknown/custom types fall back to priority 0 + neutral.
     */
    private const TYPES = [
        // — important —
        'out_of_stock'          => ['priority' => 3, 'tone' => 'danger'],
        'expired_stock'         => ['priority' => 3, 'tone' => 'danger'],
        'refund_pending'        => ['priority' => 3, 'tone' => 'danger'],
        'expiring_stock'        => ['priority' => 2, 'tone' => 'warning'],
        'order_pending'         => ['priority' => 2, 'tone' => 'warning'],
        'low_stock'             => ['priority' => 1, 'tone' => 'warning'],
        'order_confirmed'       => ['priority' => 1, 'tone' => 'info'],
        'order_ready'           => ['priority' => 1, 'tone' => 'info'],
        // — informational —
        'payment_proof'         => ['priority' => 0, 'tone' => 'info'],
        'prescription_uploaded' => ['priority' => 0, 'tone' => 'info'],
        'order_completed'       => ['priority' => 0, 'tone' => 'success'],
        'new_customer'          => ['priority' => 0, 'tone' => 'success'],
        'staff_request'         => ['priority' => 0, 'tone' => 'info'],
        'order_cancelled'       => ['priority' => 0, 'tone' => 'neutral'],
    ];

    // ══════════════════════════════════════════════════════════════════════
    //  ENDPOINTS
    // ══════════════════════════════════════════════════════════════════════

    /**
     * GET /admin/notifications  (JSON)
     * Refreshes the alerts, then returns the feed: pinned important items
     * first (highest priority, then newest), informational items after.
     */
    public function index(Request $request): JsonResponse
    {
        $this->sync();

        $limit = min(max((int) $request->query('limit', self::DEFAULT_LIMIT), 1), self::MAX_LIMIT);

        $items = Notification::active()
            ->orderByDesc('priority')          // 3,2,1 pinned … 0 informational last
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->map(fn (Notification $n) => $this->present($n))
            ->values();

        return response()->json([
            'unread'    => Notification::active()->where('is_read', false)->count(),
            'important' => Notification::important()->count(),
            'items'     => $items,
        ]);
    }

    /**
     * GET /admin/notifications/view
     * Target of the bell's "View all notifications" button: sends the admin to
     * the Settings page, which opens its Notifications row straight away.
     */
    public function viewAll(): RedirectResponse
    {
        return redirect()->route('admin.settings', ['open' => 'notifications']);
    }

    /**
     * GET /admin/notifications/all  (JSON)
     * The full, paginated list shown in Settings → Notifications.
     * Same ordering as the bell (pinned important first, then newest).
     *
     *   ?filter=all|unread|important   (default: all)
     *   ?page=N&per_page=N             (default 20, max 50)
     */
    public function all(Request $request): JsonResponse
    {
        // Refresh the alerts once per "open" (first page), not on every "load more".
        if ((int) $request->query('page', 1) <= 1) {
            $this->sync();
        }

        $filter  = $request->query('filter', 'all');
        $perPage = min(max((int) $request->query('per_page', 20), 1), 50);

        $query = Notification::active();

        if ($filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($filter === 'important') {
            $query->where('priority', '>', 0);
        }

        $page = $query
            ->orderByDesc('priority')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json([
            'unread'    => Notification::active()->where('is_read', false)->count(),
            'important' => Notification::important()->count(),
            'total'     => $page->total(),
            'has_more'  => $page->hasMorePages(),
            'next_page' => $page->hasMorePages() ? $page->currentPage() + 1 : null,
            'items'     => $page->getCollection()->map(fn (Notification $n) => $this->present($n))->values(),
        ]);
    }

    /** PUT /admin/notifications/{notification}/read */
    public function markRead(Notification $notification): JsonResponse
    {
        // Read ≠ handled: important notifications stay pinned until resolved.
        $notification->update(['is_read' => true]);

        return response()->json(['status' => 'ok']);
    }

    /** PUT /admin/notifications/read-all */
    public function markAllRead(): JsonResponse
    {
        Notification::active()->where('is_read', false)->update(['is_read' => true]);

        return response()->json(['status' => 'ok']);
    }

    // ══════════════════════════════════════════════════════════════════════
    //  PUBLIC HELPER — for any other notification type
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Push a custom notification from anywhere in the app, e.g.
     *
     *   AdminNotificationsController::push(
     *       'new_customer', 'New customer', 'Juan registered', route('admin.dashboard'),
     *       refKey: 'new_customer:42'
     *   );
     *
     * Types listed in self::TYPES get their priority/tone; others are
     * informational. Passing a $refKey makes the call idempotent.
     */
    public static function push(
        string $type,
        string $title,
        string $message,
        ?string $link = null,
        ?string $refKey = null
    ): Notification {
        $attrs = [
            'type'     => $type,
            'title'    => $title,
            'message'  => $message,
            'link'     => $link,
            'priority' => self::TYPES[$type]['priority'] ?? 0,
        ];

        return $refKey
            ? Notification::updateOrCreate(['ref_key' => $refKey], $attrs)
            : Notification::create($attrs);
    }

    // ══════════════════════════════════════════════════════════════════════
    //  SYNC — turn live data into notification rows
    // ══════════════════════════════════════════════════════════════════════

    /** Can also be called from other controllers after e.g. a stock/order change. */
    public function sync(): void
    {
        // IMPORTANT alerts: exist only while the problem exists.
        $alerts = collect()
            ->concat($this->stockLevelAlerts())
            ->concat($this->expiryAlerts())
            ->concat($this->openOrderAlerts())
            ->concat($this->refundAlerts());

        // INFORMATIONAL events: things that happened recently.
        $events = collect()
            ->concat($this->completedOrderEvents())
            ->concat($this->cancelledOrderEvents())
            ->concat($this->paymentProofEvents())
            ->concat($this->prescriptionEvents())
            ->concat($this->newCustomerEvents())
            ->concat($this->staffRequestEvents());

        $this->store($alerts->concat($events));

        // Anything important that is NOT in the current alert set has been handled.
        Notification::important()
            ->whereNotNull('ref_key')
            ->whereNotIn('ref_key', $alerts->pluck('ref_key')->all())
            ->update(['resolved_at' => now(), 'is_read' => true]);

        // Staff requests that were accepted/rejected (or deleted) no longer need a notification.
        Notification::where('type', 'staff_request')
            ->whereNotIn('ref_key', $events->where('type', 'staff_request')->pluck('ref_key')->all())
            ->delete();

        // Housekeeping.
        Notification::where('updated_at', '<', now()->subDays(self::PRUNE_AFTER_DAYS))
            ->where(fn ($q) => $q->where('priority', 0)->orWhereNotNull('resolved_at'))
            ->delete();
    }

    /** Insert new rows / refresh existing ones (matched on ref_key). */
    private function store(Collection $items): void
    {
        if ($items->isEmpty()) {
            return;
        }

        $existing = Notification::whereIn('ref_key', $items->pluck('ref_key')->all())
            ->get()
            ->keyBy('ref_key');

        foreach ($items as $item) {
            try {
                $n = $existing->get($item['ref_key']);

                if (! $n) {
                    $n = new Notification();
                    $n->ref_key    = $item['ref_key'];
                    $n->is_read    = false;
                    $n->created_at = $item['at'];
                } elseif ($n->resolved_at) {
                    // The problem came back → reopen it as a fresh, unread alert.
                    $n->resolved_at = null;
                    $n->is_read     = false;
                    $n->created_at  = now();
                }

                $n->fill(Arr::only($item, ['type', 'priority', 'title', 'message', 'link']));
                $n->save();
            } catch (QueryException $e) {
                // Two requests inserted the same ref_key at once (unique index) — safe to ignore.
                if ((string) $e->getCode() !== '23000') {
                    throw $e;
                }
            }
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  IMPORTANT ALERTS (auto-resolve when fixed)
    // ══════════════════════════════════════════════════════════════════════

    /** Out of stock + low stock, per active product. */
    private function stockLevelAlerts(): array
    {
        $today = today();

        // Sellable quantity = active, not-yet-expired batches.
        $rows = DB::table('products')
            ->leftJoin('stocks', function ($join) use ($today) {
                $join->on('stocks.product_id', '=', 'products.id')
                    ->where('stocks.is_active', true)
                    ->where(function ($q) use ($today) {
                        $q->whereNull('stocks.expiry_date')
                          ->orWhereDate('stocks.expiry_date', '>=', $today);
                    });
            })
            ->where('products.is_active', true)
            ->groupBy('products.id', 'products.name')
            ->select('products.id', 'products.name', DB::raw('COALESCE(SUM(stocks.quantity), 0) AS available'))
            ->havingRaw('COALESCE(SUM(stocks.quantity), 0) <= ?', [self::LOW_STOCK_THRESHOLD])
            ->get();

        $alerts = [];

        foreach ($rows as $r) {
            $qty = (int) $r->available;

            if ($qty <= 0) {
                $alerts[] = $this->alert(
                    'out_of_stock', "out_of_stock:product:{$r->id}",
                    'Out of stock',
                    "{$r->name} is out of stock.",
                    route('admin.stocks', ['status' => 'out-of-stock'])
                );
            } else {
                $alerts[] = $this->alert(
                    'low_stock', "low_stock:product:{$r->id}",
                    'Low stock',
                    "{$r->name} has only {$qty} " . Str::plural('unit', $qty) . ' left.',
                    route('admin.stocks', ['status' => 'low-stock'])
                );
            }
        }

        return $alerts;
    }

    /** Expired batches still holding units + batches expiring within EXPIRING_DAYS. */
    private function expiryAlerts(): array
    {
        $today = today();

        $rows = DB::table('stocks')
            ->join('products', 'products.id', '=', 'stocks.product_id')
            ->where('stocks.quantity', '>', 0)
            ->whereNotNull('stocks.expiry_date')
            ->whereDate('stocks.expiry_date', '<=', $today->copy()->addDays(self::EXPIRING_DAYS))
            ->orderBy('stocks.expiry_date')
            ->select('stocks.id', 'stocks.quantity', 'stocks.expiry_date', 'products.name')
            ->get();

        $alerts = [];

        foreach ($rows as $r) {
            $expiry = Carbon::parse($r->expiry_date)->startOfDay();
            $days   = (int) $today->diffInDays($expiry, false);   // negative = already expired
            $units  = $r->quantity . ' ' . Str::plural('unit', $r->quantity);

            if ($days < 0) {
                $ago = abs($days);
                $alerts[] = $this->alert(
                    'expired_stock', "expired_stock:stock:{$r->id}",
                    'Expired stock',
                    "{$r->name} expired {$ago} " . Str::plural('day', $ago) . " ago — {$units} still on record.",
                    route('admin.stocks', ['status' => 'expired'])
                );
            } else {
                $when = $days === 0 ? 'expires today' : "expires in {$days} " . Str::plural('day', $days);
                $alerts[] = $this->alert(
                    'expiring_stock', "expiring_stock:stock:{$r->id}",
                    'Stock expiring soon',
                    "{$r->name} {$when} ({$units}).",
                    route('admin.stocks', ['status' => 'expiring-soon'])
                );
            }
        }

        return $alerts;
    }

    /** Ongoing / unfinished orders: pending, confirmed, ready for pickup. */
    private function openOrderAlerts(): array
    {
        $orders = $this->orderQuery()
            ->whereIn('orders.status', self::OPEN_STATUSES)
            ->orderBy('orders.created_at')
            ->get();

        $alerts = [];

        foreach ($orders as $o) {
            $summary = $this->orderSummary($o);

            [$type, $title, $tail] = match ($o->status) {
                'pending'   => ['order_pending',   'New order — needs confirmation', 'waiting for confirmation'],
                'confirmed' => ['order_confirmed', 'Order being prepared',           'confirmed, not yet ready for pickup'],
                default     => ['order_ready',     'Order ready for pickup',         'waiting for the customer to pick up'],
            };

            if ($o->status === 'pending' && $o->payment_method === 'app' && $o->proof_of_payment) {
                $tail .= ' (payment proof uploaded)';
            }

            $alerts[] = $this->alert(
                $type, "order_open:{$o->id}", $title,
                "{$summary} — {$tail}.",
                route('admin.orders'),
                Carbon::parse($o->created_at)
            );
        }

        return $alerts;
    }

    /** Cancelled orders that were paid through an app but whose refund hasn't been sent/uploaded yet. */
    private function refundAlerts(): array
    {
        $orders = $this->orderQuery()
            ->where('orders.status', 'cancelled')
            ->where('orders.payment_method', 'app')
            ->whereNotNull('orders.proof_of_payment')
            ->whereNull('orders.proof_of_refund')
            ->orderBy('orders.cancelled_at')
            ->get();

        $alerts = [];

        foreach ($orders as $o) {
            $alerts[] = $this->alert(
                'refund_pending', "refund_pending:{$o->id}",
                'Refund needed',
                $this->orderSummary($o) . ' — cancelled after payment; refund proof not uploaded yet.',
                route('admin.orders'),
                Carbon::parse($o->cancelled_at ?? $o->updated_at)
            );
        }

        return $alerts;
    }

    // ══════════════════════════════════════════════════════════════════════
    //  INFORMATIONAL EVENTS
    // ══════════════════════════════════════════════════════════════════════

    private function completedOrderEvents(): array
    {
        $orders = $this->orderQuery()
            ->where('orders.status', 'picked_up')
            ->where('orders.picked_up_at', '>=', $this->since())
            ->orderByDesc('orders.picked_up_at')
            ->limit(self::PER_TYPE_CAP)
            ->get();

        return $orders->map(fn ($o) => $this->alert(
            'order_completed', "order_completed:{$o->id}",
            'Order completed',
            $this->orderSummary($o) . ' — picked up and paid.',
            route('admin.orders'),
            Carbon::parse($o->picked_up_at)
        ))->all();
    }

    private function cancelledOrderEvents(): array
    {
        $orders = $this->orderQuery()
            ->where('orders.status', 'cancelled')
            ->where('orders.cancelled_at', '>=', $this->since())
            ->orderByDesc('orders.cancelled_at')
            ->limit(self::PER_TYPE_CAP)
            ->get();

        return $orders->map(function ($o) {
            $reason = $o->cancellation_reason ? ' Reason: ' . Str::limit($o->cancellation_reason, 80) : '';

            return $this->alert(
                'order_cancelled', "order_cancelled:{$o->id}",
                'Order cancelled',
                $this->orderSummary($o) . ' was cancelled.' . $reason,
                route('admin.orders'),
                Carbon::parse($o->cancelled_at)
            );
        })->all();
    }

    /** Customer uploaded a payment screenshot on an order that's still open. */
    private function paymentProofEvents(): array
    {
        $orders = $this->orderQuery()
            ->whereIn('orders.status', self::OPEN_STATUSES)
            ->where('orders.payment_method', 'app')
            ->whereNotNull('orders.proof_of_payment')
            ->where('orders.updated_at', '>=', $this->since())
            ->orderByDesc('orders.updated_at')
            ->limit(self::PER_TYPE_CAP)
            ->get();

        return $orders->map(fn ($o) => $this->alert(
            'payment_proof', "payment_proof:{$o->id}",
            'Payment proof uploaded',
            $this->orderSummary($o) . ' — check the payment screenshot.',
            route('admin.orders'),
            Carbon::parse($o->updated_at)
        ))->all();
    }

    /** Customer uploaded a prescription for a line item on an open order. */
    private function prescriptionEvents(): array
    {
        $rows = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->whereNull('orders.deleted_at')
            ->whereIn('orders.status', self::OPEN_STATUSES)
            ->whereNotNull('order_items.prescription_file')
            ->where('order_items.updated_at', '>=', $this->since())
            ->orderByDesc('order_items.updated_at')
            ->limit(self::PER_TYPE_CAP)
            ->select('order_items.id', 'order_items.updated_at', 'orders.order_number', 'products.name')
            ->get();

        return $rows->map(fn ($r) => $this->alert(
            'prescription_uploaded', "prescription:{$r->id}",
            'Prescription uploaded',
            "Prescription for {$r->name} on order {$r->order_number} — needs verification.",
            route('admin.orders'),
            Carbon::parse($r->updated_at)
        ))->all();
    }

    private function newCustomerEvents(): array
    {
        $users = DB::table('users')
            ->whereNull('deleted_at')
            ->where('created_at', '>=', $this->since())
            ->orderByDesc('created_at')
            ->limit(self::PER_TYPE_CAP)
            ->select('id', 'first_name', 'last_name', 'created_at')
            ->get();

        return $users->map(fn ($u) => $this->alert(
            'new_customer', "new_customer:{$u->id}",
            'New customer',
            trim("{$u->first_name} {$u->last_name}") . ' just registered.',
            route('admin.dashboard'),
            Carbon::parse($u->created_at)
        ))->all();
    }

    /**
     * A staff member registered and is waiting for approval.
     * Informational only (priority 0) — it is removed once the request is handled.
     */
    private function staffRequestEvents(): array
    {
        return Staff::pending()
            ->orderByDesc('created_at')
            ->limit(self::PER_TYPE_CAP)
            ->get()
            ->map(fn (Staff $s) => $this->alert(
                'staff_request', "staff_request:{$s->id}",
                'New staff request',
                trim("{$s->first_name} {$s->last_name}") . ' registered a staff account and is waiting for approval.',
                route('admin.staffs', ['open_requests' => 1]),
                $s->created_at
            ))
            ->all();
    }

    // ══════════════════════════════════════════════════════════════════════
    //  SHARED HELPERS
    // ══════════════════════════════════════════════════════════════════════

    private function since(): Carbon
    {
        return now()->subDays(self::INFO_WINDOW_DAYS);
    }

    /** Base orders query: live (non-deleted) orders joined with their customer + item count. */
    private function orderQuery()
    {
        return DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->whereNull('orders.deleted_at')
            ->select(
                'orders.id', 'orders.order_number', 'orders.status', 'orders.total',
                'orders.payment_method', 'orders.proof_of_payment', 'orders.cancellation_reason',
                'orders.created_at', 'orders.updated_at', 'orders.cancelled_at', 'orders.picked_up_at',
                'users.first_name', 'users.last_name',
                DB::raw('(SELECT COALESCE(SUM(oi.quantity), 0) FROM order_items oi WHERE oi.order_id = orders.id) AS item_count')
            );
    }

    /** "Order ORD-… · Juan dela Cruz · 5 items · ₱620.00" */
    private function orderSummary(object $o): string
    {
        $name  = trim("{$o->first_name} {$o->last_name}") ?: 'Customer';
        $items = (int) $o->item_count;

        return "Order {$o->order_number} · {$name} · {$items} " . Str::plural('item', $items)
            . ' · ₱' . number_format((float) $o->total, 2);
    }

    /** Normalised alert/event array consumed by store(). */
    private function alert(
        string $type,
        string $refKey,
        string $title,
        string $message,
        string $link,
        ?Carbon $at = null
    ): array {
        return [
            'ref_key'  => $refKey,
            'type'     => $type,
            'priority' => self::TYPES[$type]['priority'] ?? 0,
            'title'    => $title,
            'message'  => $message,
            'link'     => $link,
            'at'       => $at ?? now(),
        ];
    }

    /** Row → JSON shape used by the dropdown. */
    private function present(Notification $n): array
    {
        return [
            'id'        => $n->id,
            'type'      => $n->type,
            'tone'      => self::TYPES[$n->type]['tone'] ?? 'neutral',
            'important' => $n->priority > 0 && $n->resolved_at === null,
            'level'     => $n->priority,
            'title'     => $n->title ?: Str::headline($n->type),
            'message'   => $n->message,
            'link'      => $n->link,
            'is_read'   => $n->is_read,
            'time'      => optional($n->created_at)->diffForHumans(),
        ];
    }
}