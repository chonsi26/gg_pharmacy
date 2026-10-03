<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrdersController extends Controller
{
    /** How long a "ready" (For Pick Up) order is held before it expires. */
    public const PICKUP_WINDOW_HOURS = 5;

    /** Cancellation reason recorded on orders that were never picked up in time. */
    public const EXPIRED_REASON = 'Order expired';

    /**
     * Maps DB statuses to the keys the orders.blade.php UI uses.
     * ready → "approved" (the "For Pick Up" tab), picked_up → "completed".
     */
    private const UI_STATUS = [
        'pending'   => 'pending',
        'confirmed' => 'confirmed',
        'ready'     => 'approved',
        'picked_up' => 'completed',
        'cancelled' => 'cancelled',
    ];

    public function __construct(private readonly OrderService $orderService)
    {
    }

    // ─────────────────────────────────────────────────────────────────────
    //  Data feed for the Sales Records page
    // ─────────────────────────────────────────────────────────────────────

    /**
     * JSON feed consumed by orders.blade.php. Every call first sweeps
     * expired pickups, so the table never shows a stale "For Pick Up" order
     * that has already run past its 5-hour window.
     */
    public function data(): JsonResponse
    {
        $expired = $this->autoExpireReadyOrders();

        $orders = Order::with([
                'user' => fn ($q) => $q->withTrashed(),
                'items.product',
            ])
            ->latest()
            ->get()
            ->map(fn (Order $order) => $this->transform($order))
            ->values();

        return response()->json([
            'orders'        => $orders,
            'expired_count' => $expired,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  Auto-expiry (5-hour pickup window)
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Cancels every order that has been "ready" for pickup for 5 hours or
     * more, with the cancellation reason "Order expired", and returns the
     * reserved stock to its batch.
     *
     * Safe to call repeatedly / from several places (page loads, the JS
     * poller, the scheduler): each order is re-checked under a row lock
     * inside its own transaction, so it can't be cancelled or restocked
     * twice, and an order picked up a moment earlier is left alone.
     *
     * @return int number of orders that were expired by this call
     */
    public function autoExpireReadyOrders(): int
    {
        $cutoff = now()->subHours(self::PICKUP_WINDOW_HOURS);

        $candidateIds = Order::where('status', 'ready')
            ->whereNotNull('ready_at')
            ->where('ready_at', '<=', $cutoff)
            ->pluck('id');

        $expired = 0;

        foreach ($candidateIds as $id) {
            DB::transaction(function () use ($id, $cutoff, &$expired) {
                $order = Order::lockForUpdate()->find($id);

                // Re-check now that we hold the lock.
                if (
                    ! $order
                    || $order->status !== 'ready'
                    || $order->ready_at === null
                    || $order->ready_at->gt($cutoff)
                ) {
                    return;
                }

                $this->orderService->releaseStock($order);
                $order->markCancelled(self::EXPIRED_REASON);

                $expired++;
            });
        }

        return $expired;
    }

    // ─────────────────────────────────────────────────────────────────────
    //  Status transitions
    // ─────────────────────────────────────────────────────────────────────

    /** Pending → Confirmed ("Approve" button). */
    public function confirm(Order $order): JsonResponse
    {
        if ($order->status !== 'pending') {
            return $this->fail('Only pending orders can be approved.');
        }

        $order->markConfirmed();

        return $this->ok("Order {$order->order_number} confirmed.", $order);
    }

    /** Confirmed → Ready / For Pick Up ("Reserve" button). Starts the 5-hour clock. */
    public function ready(Order $order): JsonResponse
    {
        if ($order->status !== 'confirmed') {
            return $this->fail('Only confirmed orders can be reserved for pick up.');
        }

        if ($order->isOnlinePayment() && ! $order->hasProofOfPayment()) {
            return $this->fail('The customer has not uploaded proof of payment yet.');
        }

        $order->markReady();

        return $this->ok("Order {$order->order_number} is now ready for pick up.", $order);
    }

    /** Ready → Picked up / Completed (receipt "Submit"). */
    public function complete(Order $order): JsonResponse
    {
        // Sweep first: an order past its window must not be completable.
        $this->autoExpireReadyOrders();
        $order->refresh();

        if ($order->status === 'cancelled' && $order->cancellation_reason === self::EXPIRED_REASON) {
            return $this->fail('This order has expired and was cancelled.');
        }

        if ($order->status !== 'ready') {
            return $this->fail('Only orders that are ready for pick up can be completed.');
        }

        $order->markPickedUp();

        return $this->ok("Order {$order->order_number} completed.", $order);
    }

    /** Cancel from any non-terminal state; restocks the reserved quantity. */
    public function cancel(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $reason = trim($validated['reason'] ?? '') ?: 'Cancelled by pharmacy';

        $cancelled = DB::transaction(function () use ($order, $reason) {
            $locked = Order::lockForUpdate()->find($order->id);

            if (! $locked || $locked->isTerminal()) {
                return false;
            }

            $this->orderService->releaseStock($locked);
            $locked->markCancelled($reason);

            return true;
        });

        if (! $cancelled) {
            return $this->fail('This order can no longer be cancelled.');
        }

        return $this->ok("Order {$order->order_number} cancelled — {$reason}.", $order->refresh());
    }

    /**
     * Record a refund for a cancelled order the customer already paid for
     * online. Requires an uploaded proof-of-refund image and can only be done
     * once per order.
     */
    public function refund(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'proof_of_refund' => ['required', 'image', 'max:5120'], // 5 MB
        ], [
            'proof_of_refund.required' => 'Please upload a proof of refund.',
            'proof_of_refund.image'    => 'The proof of refund must be an image.',
            'proof_of_refund.max'      => 'The proof of refund may not be larger than 5 MB.',
        ]);

        if (! $order->needsRefund()) {
            return $this->fail('Only cancelled orders that were paid online can be refunded.');
        }

        if ($order->hasProofOfRefund()) {
            return $this->fail('This order has already been refunded.');
        }

        $path = $request->file('proof_of_refund')->store('refund-proofs', 'public');

        // Stored as a public-relative URL path so asset() can serve it,
        // matching how proof_of_payment is exposed in transform().
        $order->update(['proof_of_refund' => 'storage/' . $path]);

        return $this->ok("Refund recorded for order {$order->order_number}.", $order);
    }

    /** Soft-delete a finished (completed or cancelled) order. */
    public function destroy(Order $order): JsonResponse
    {
        if (! $order->isTerminal()) {
            return $this->fail('Only completed or cancelled orders can be deleted.');
        }

        $order->delete();

        return $this->ok("Order {$order->order_number} deleted.");
    }

    // ─────────────────────────────────────────────────────────────────────
    //  Helpers
    // ─────────────────────────────────────────────────────────────────────

    /** Shapes an Order into the object orders.blade.php's JS expects. */
    private function transform(Order $order): array
    {
        $dueAt = $order->status === 'ready' && $order->ready_at
            ? $order->ready_at->copy()->addHours(self::PICKUP_WINDOW_HOURS)
            : null;

        return [
            'dbId'         => $order->id,
            'id'           => $order->order_number,
            'customer'     => $order->user?->full_name ?? 'Deleted user',
            'date'         => $order->created_at->format('M j · g:i A'),
            'payment'      => $order->isOnlinePayment() ? 'online' : 'cash',
            'status'       => self::UI_STATUS[$order->status] ?? $order->status,
            'paidOnline'   => $order->isOnlinePayment() && $order->hasProofOfPayment(),
            'proofImage'   => $order->hasProofOfPayment() ? asset($order->proof_of_payment) : null,
            'cancelReason' => $order->cancellation_reason,
            'refunded'     => $order->needsRefund() && $order->hasProofOfRefund(),
            'refundProof'  => $order->hasProofOfRefund() ? asset($order->proof_of_refund) : null,
            'dueDate'      => $dueAt?->format('M j · g:i A'),
            'dueAt'        => $dueAt?->toIso8601String(),
            'items'        => $order->items->map(fn ($item) => [
                'name'                 => $item->product?->name ?? 'Removed product',
                'qty'                  => $item->quantity,
                'price'                => $item->unit_price,
                'requiresPrescription' => $item->requiresPrescription(),
                'prescriptionImage'    => $item->prescription_file ? asset($item->prescription_file) : null,
            ])->values(),
        ];
    }

    private function ok(string $message, ?Order $order = null): JsonResponse
    {
        return response()->json([
            'status'  => 'ok',
            'message' => $message,
            'order'   => $order ? $this->transform($order->load(['user' => fn ($q) => $q->withTrashed(), 'items.product'])) : null,
        ]);
    }

    private function fail(string $message, int $code = 422): JsonResponse
    {
        return response()->json(['status' => 'error', 'message' => $message], $code);
    }
}