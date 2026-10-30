<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Customer notifications (the bell in home.blade.php).
 *
 * How it works
 * ────────────
 * Notifications are DERIVED from the customer's real data every time the bell
 * loads (index → sync). That means nothing else in the app has to be changed:
 * when an order moves to "confirmed", or the customer uploads a proof of
 * payment, the next sync picks it up.
 *
 *   • Status notifications   – order placed / confirmed / ready / picked up /
 *                              cancelled / refund sent         (normal)
 *   • Action-required items  – proof of payment, prescription upload,
 *                              reservation about to expire     (PRIORITY)
 *   • Wishlist notifications – on sale, running low on stock   (normal)
 *   • System                 – welcome message                 (normal)
 *
 * PRIORITY notifications stay pinned at the top of the bell until the customer
 * handles them (or the order ends). Once the underlying condition no longer
 * holds, sync() stamps resolved_at and they drop into the normal list.
 *
 * Optional config (config/orders.php) – sensible defaults are used otherwise:
 *   'proof_window_hours'   => 24,  // time to upload proof for app-paid orders
 *   'pickup_window_days'   => 3,   // time to collect an order once "ready"
 *   'expiry_warning_hours' => 6,   // start warning this long before a deadline
 */
class UserNotificationsController extends Controller
{
    private const TERMINAL = ['picked_up', 'cancelled'];

    /** type => [css tone used by the bell, Font Awesome icon] */
    private const TYPE_META = [
        'order_placed'          => ['order',    'fa-receipt'],
        'order_confirmed'       => ['order',    'fa-check'],
        'order_ready'           => ['order',    'fa-box-open'],
        'order_picked_up'       => ['system',   'fa-check-circle'],
        'order_cancelled'       => ['promo',    'fa-ban'],
        'refund_sent'           => ['system',   'fa-money-bill-wave'],
        'proof_required'        => ['alert',    'fa-file-upload'],
        'prescription_required' => ['alert',    'fa-prescription'],
        'order_expiring'        => ['alert',    'fa-hourglass-half'],
        'wishlist_sale'         => ['wishlist', 'fa-tag'],
        'wishlist_low_stock'    => ['wishlist', 'fa-exclamation-circle'],
        'wishlist_restock'      => ['wishlist', 'fa-heart'],
        'welcome'               => ['system',   'fa-heart'],
        'promo'                 => ['promo',    'fa-percent'],
    ];

    // ══════════════════════════════════════════════════════════════════════
    //  HTTP endpoints (JSON)
    // ══════════════════════════════════════════════════════════════════════

    /** GET /notifications[?all=1] */
    public function index(Request $request): JsonResponse
    {
        $userId = Auth::id();

        $this->sync($userId);

        return response()->json($this->payload($userId, $request->boolean('all')));
    }

    /** PUT /notifications/{notification}/read */
    public function markRead(UserNotification $notification): JsonResponse
    {
        abort_unless($notification->user_id === Auth::id(), 404);

        if (! $notification->is_read) {
            $notification->update(['is_read' => true, 'read_at' => now()]);
        }

        return response()->json($this->payload(Auth::id()));
    }

    /**
     * PUT /notifications/read-all
     *
     * Clears every unread dot. Priority items that still need action are marked
     * read too, but they STAY pinned and keep counting in the badge until the
     * customer actually handles them.
     */
    public function markAllRead(): JsonResponse
    {
        $userId = Auth::id();

        UserNotification::forUser($userId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json($this->payload($userId));
    }

    // ══════════════════════════════════════════════════════════════════════
    //  Public helpers – call these from other controllers if you want an
    //  INSTANT notification instead of waiting for the next bell refresh.
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Create (once) a notification for a user.
     *
     * $opts: url, order_id, product_id, priority (bool), expires_at, event_at,
     *        read (bool, create already-read), refresh (bool, update if it exists)
     */
    public static function push(
        int $userId,
        string $key,
        string $type,
        string $title,
        string $message,
        array $opts = []
    ): ?UserNotification {
        $n = UserNotification::where('user_id', $userId)->where('dedupe_key', $key)->first();

        if (! $n) {
            try {
                return UserNotification::create([
                    'user_id'     => $userId,
                    'dedupe_key'  => $key,
                    'type'        => $type,
                    'title'       => $title,
                    'message'     => $message,
                    'url'         => $opts['url'] ?? null,
                    'order_id'    => $opts['order_id'] ?? null,
                    'product_id'  => $opts['product_id'] ?? null,
                    'is_priority' => (bool) ($opts['priority'] ?? false),
                    'expires_at'  => $opts['expires_at'] ?? null,
                    'event_at'    => $opts['event_at'] ?? now(),
                    'is_read'     => (bool) ($opts['read'] ?? false),
                    'read_at'     => ! empty($opts['read']) ? now() : null,
                ]);
            } catch (QueryException $e) {
                // Two tabs synced at the same moment; the other one won. Fine.
                return null;
            }
        }

        if (! empty($opts['refresh'])) {
            $typeChanged = $n->type !== $type;

            $n->fill([
                'type'       => $type,
                'title'      => $title,
                'message'    => $message,
                'url'        => $opts['url'] ?? $n->url,
                'expires_at' => $opts['expires_at'] ?? null,
            ]);

            if ($n->resolved_at) {            // the problem came back → re-pin it
                $n->resolved_at = null;
                $n->is_read     = false;
            } elseif ($typeChanged) {         // e.g. "upload proof" → "expiring!"
                $n->is_read = false;
            }

            $n->save();
        }

        return $n;
    }

    /** Send the same notification to every active customer (promos, announcements). */
    public static function broadcast(string $title, string $message, string $type = 'promo', ?string $url = null): int
    {
        $batch = 'broadcast:' . Str::uuid();
        $count = 0;

        DB::table('users')
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->pluck('id')
            ->each(function ($id) use ($batch, $type, $title, $message, $url, &$count) {
                self::push((int) $id, $batch, $type, $title, $message, ['url' => $url]);
                $count++;
            });

        return $count;
    }

    /**
     * Tell everyone who wishlisted a product that it's back in stock.
     * Call it right after a stock batch is added / activated, e.g. from
     * AdminStocksController@store and StaffStocksController@store:
     *
     *     UserNotificationsController::backInStock($stock->product_id);
     */
    public static function backInStock(int $productId): void
    {
        $product = DB::table('products')->where('id', $productId)->where('is_active', true)->first();
        if (! $product) {
            return;
        }

        $stamp = now()->format('YmdHi');

        DB::table('wishlists')->where('product_id', $productId)->pluck('user_id')->each(
            fn ($uid) => self::push(
                (int) $uid,
                "wishlist:{$productId}:restock:{$stamp}",
                'wishlist_restock',
                $product->name,
                'from your wishlist is back in stock.',
                ['product_id' => $productId, 'url' => route('product.show', $productId, false)]
            )
        );
    }

    // ══════════════════════════════════════════════════════════════════════
    //  Sync – derives notifications from the customer's data
    // ══════════════════════════════════════════════════════════════════════

    private function sync(int $userId): void
    {
        $activePinned = [];   // dedupe_keys of priority items that are STILL outstanding

        // Non-terminal orders (any age) + recently finished ones.
        $orders = DB::table('orders')
            ->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->where(function ($q) {
                $q->whereNotIn('status', self::TERMINAL)
                  ->orWhere('updated_at', '>=', now()->subDays(30));
            })
            ->orderBy('id')
            ->get();

        $items = $orders->isEmpty()
            ? collect()
            : DB::table('order_items')
                ->join('products', 'products.id', '=', 'order_items.product_id')
                ->whereIn('order_items.order_id', $orders->pluck('id'))
                ->get([
                    'order_items.id as item_id',
                    'order_items.order_id',
                    'order_items.prescription_file',
                    'products.name',
                    'products.requires_prescription',
                ])
                ->groupBy('order_id');

        foreach ($orders as $order) {
            $this->syncOrderStatus($userId, $order);

            if (! in_array($order->status, self::TERMINAL, true)) {
                array_push($activePinned, ...$this->syncOrderActions($userId, $order, $items->get($order->id, collect())));
            }
        }

        $this->syncWishlist($userId);

        self::push($userId, 'welcome', 'welcome', 'Welcome!', 'Thanks for joining us. Your order updates and reminders will show up here.');

        // Anything that was pinned but is no longer outstanding → resolved.
        UserNotification::forUser($userId)
            ->pinned()
            ->when($activePinned, fn ($q) => $q->whereNotIn('dedupe_key', $activePinned))
            ->update(['resolved_at' => now(), 'is_read' => true, 'read_at' => now()]);

        // Housekeeping: drop old, finished notifications.
        UserNotification::forUser($userId)
            ->where('event_at', '<', now()->subDays(90))
            ->where('type', '!=', 'welcome')
            ->where(fn ($q) => $q->where('is_priority', false)->orWhereNotNull('resolved_at'))
            ->delete();
    }

    /** Normal (non-priority) notifications – one per status the order has reached. */
    private function syncOrderStatus(int $userId, object $order): void
    {
        $no  = $order->order_number;
        $url = route('order.track', $order->id, false);
        $o   = ['order_id' => $order->id, 'url' => $url];

        $put = function (string $stage, string $type, string $message, ?Carbon $at) use ($userId, $order, $no, $o) {
            $at ??= $this->ts($order->updated_at) ?? now();
            self::push($userId, "order:{$order->id}:status:{$stage}", $type, "Order {$no}", $message, $o + [
                'event_at' => $at,
                // Old history shouldn't light up the badge on first sync.
                'read'     => $at->lt(now()->subDays(3)),
            ]);
        };

        $put('pending', 'order_placed',
            'has been placed. Waiting for the pharmacy to confirm your reservation.',
            $this->ts($order->created_at));

        if ($order->confirmed_at || $order->status === 'confirmed') {
            $put('confirmed', 'order_confirmed',
                'was confirmed by the pharmacy. We\'re preparing your items.',
                $this->ts($order->confirmed_at));
        }

        if ($order->ready_at || $order->status === 'ready') {
            $put('ready', 'order_ready',
                'is ready for pickup. Please collect it at the pharmacy.',
                $this->ts($order->ready_at));
        }

        if ($order->picked_up_at || $order->status === 'picked_up') {
            $put('picked_up', 'order_picked_up',
                'was picked up. Thank you for your purchase!',
                $this->ts($order->picked_up_at));
        }

        if ($order->cancelled_at || $order->status === 'cancelled') {
            $msg = 'was cancelled.';
            if (! empty($order->cancellation_reason)) {
                $msg .= ' Reason: ' . Str::limit($order->cancellation_reason, 120);
            }
            if ($order->payment_method === 'app' && ! empty($order->proof_of_payment) && empty($order->proof_of_refund)) {
                $msg .= ' Your refund will be sent to your payment account.';
            }
            $put('cancelled', 'order_cancelled', $msg, $this->ts($order->cancelled_at));

            if ($order->payment_method === 'app' && ! empty($order->proof_of_refund)) {
                $put('refund', 'refund_sent',
                    'has been refunded. You can view the refund receipt in your order.',
                    $this->ts($order->updated_at));
            }
        }
    }

    /**
     * PRIORITY notifications – things the customer must do.
     * Returns the dedupe_keys that are currently outstanding.
     */
    private function syncOrderActions(int $userId, object $order, Collection $items): array
    {
        $keys   = [];
        $no     = $order->order_number;
        $o      = ['order_id' => $order->id, 'url' => route('order.track', $order->id, false), 'priority' => true, 'refresh' => true];
        $warn   = (int) config('orders.expiry_warning_hours', 6);
        $status = $order->status;

        // 1) Proof of payment (app-paid orders) – with a deadline.
        if ($order->payment_method === 'app'
            && empty($order->proof_of_payment)
            && in_array($status, ['pending', 'confirmed'], true)
        ) {
            $deadline = Carbon::parse($order->created_at)
                ->addHours((int) config('orders.proof_window_hours', 24));
            $urgent = now()->gte($deadline->copy()->subHours($warn));

            $key    = "order:{$order->id}:proof_required";
            $keys[] = $key;

            self::push(
                $userId, $key,
                $urgent ? 'order_expiring' : 'proof_required',
                "Order {$no}",
                $urgent
                    ? 'is about to expire. Upload your proof of payment now or the reservation may be cancelled.'
                    : 'needs your proof of payment. Upload your e-wallet receipt so the pharmacy can confirm it.',
                $o + ['expires_at' => $deadline]
            );
        }

        // 2) Prescription uploads for Rx items.
        $missingRx = $items->filter(fn ($i) => $i->requires_prescription && empty($i->prescription_file));

        if ($missingRx->isNotEmpty()) {
            $key    = "order:{$order->id}:prescription";
            $keys[] = $key;

            $names = $missingRx->pluck('name')->take(2)->implode(', ');
            $more  = $missingRx->count() - 2;

            self::push(
                $userId, $key, 'prescription_required', "Order {$no}",
                'needs a prescription upload for ' . $names . ($more > 0 ? " and {$more} more" : '') . '.',
                $o
            );
        }

        // 3) Ready order that must be collected before the pickup window closes.
        if ($status === 'ready') {
            $readyAt  = $this->ts($order->ready_at) ?? $this->ts($order->updated_at) ?? now();
            $deadline = $readyAt->copy()->addDays((int) config('orders.pickup_window_days', 3));

            if (now()->gte($deadline->copy()->subHours(max($warn, 24)))) {
                $key    = "order:{$order->id}:pickup_expiring";
                $keys[] = $key;

                self::push(
                    $userId, $key, 'order_expiring', "Order {$no}",
                    'is still waiting for pickup. Please collect it before the reservation expires.',
                    $o + ['expires_at' => $deadline]
                );
            }
        }

        return $keys;
    }

    /** Wishlist: on sale / running low – only for products that are actually in stock. */
    private function syncWishlist(int $userId): void
    {
        $wished = DB::table('wishlists')
            ->join('products', 'products.id', '=', 'wishlists.product_id')
            ->where('wishlists.user_id', $userId)
            ->where('products.is_active', true)
            ->orderByDesc('wishlists.created_at')
            ->limit(30)
            ->get(['products.id', 'products.name', 'products.price', 'products.old_price']);

        if ($wished->isEmpty()) {
            return;
        }

        $stock = DB::table('stocks')
            ->whereIn('product_id', $wished->pluck('id'))
            ->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', today()))
            ->groupBy('product_id')
            ->selectRaw('product_id, SUM(quantity) as qty')
            ->pluck('qty', 'product_id');

        foreach ($wished as $p) {
            $qty = (int) ($stock[$p->id] ?? 0);
            if ($qty <= 0) {
                continue;
            }

            $o = ['product_id' => $p->id, 'url' => route('product.show', $p->id, false)];

            if ($p->old_price !== null && (float) $p->old_price > (float) $p->price) {
                self::push(
                    $userId,
                    "wishlist:{$p->id}:sale:" . (int) round($p->price * 100),
                    'wishlist_sale',
                    $p->name,
                    'from your wishlist is on sale: now ₱' . number_format($p->price, 2)
                        . ' (was ₱' . number_format($p->old_price, 2) . ').',
                    $o
                );
            }

            if ($qty <= 5) {
                self::push(
                    $userId,
                    "wishlist:{$p->id}:low_stock",
                    'wishlist_low_stock',
                    $p->name,
                    "from your wishlist is running low. Only {$qty} left!",
                    $o
                );
            }
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  Output
    // ══════════════════════════════════════════════════════════════════════

    private function payload(int $userId, bool $all = false): array
    {
        $limit  = $all ? 100 : 10;
        $pinned = UserNotification::forUser($userId)->pinned()->count();
        $total  = UserNotification::forUser($userId)->count();

        $rows = UserNotification::forUser($userId)
            ->displayOrder()
            ->limit(max($limit, $pinned))   // pinned items are never cut off
            ->get();

        return [
            'badge_count'  => UserNotification::forUser($userId)->needsAttention()->count(),
            'pinned_count' => $pinned,
            'total'        => $total,
            'notifications' => $rows->map(fn (UserNotification $n) => $this->present($n))->values(),
        ];
    }

    private function present(UserNotification $n): array
    {
        [$tone, $icon] = self::TYPE_META[$n->type] ?? ['system', 'fa-bell'];

        $deadline = null;
        $overdue  = false;
        if ($n->is_pinned && $n->expires_at) {
            $overdue  = $n->expires_at->isPast();
            $deadline = $overdue
                ? 'Deadline passed ' . $n->expires_at->diffForHumans()
                : 'Due ' . $n->expires_at->diffForHumans();
        }

        return [
            'id'          => $n->id,
            'type'        => $n->type,
            'tone'        => $tone,
            'icon'        => $icon,
            'title'       => $n->title,
            'message'     => $n->message,
            'url'         => $n->url,
            'is_read'     => $n->is_read,
            'is_pinned'   => $n->is_pinned,
            'is_resolved' => $n->is_priority && $n->resolved_at !== null,
            'deadline'    => $deadline,
            'overdue'     => $overdue,
            'time'        => $n->event_at->diffForHumans(),
        ];
    }

    private function ts($value): ?Carbon
    {
        return $value ? Carbon::parse($value) : null;
    }
}
