<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
    //  Manual order entry ("Add Order" button)
    // ─────────────────────────────────────────────────────────────────────

    /** Product picker feed for the Add Order modal. */
    public function products(): JsonResponse
    {
        $products = Product::active()
            ->orderBy('name')
            ->get()
            ->map(fn (Product $p) => [
                'id'                   => $p->id,
                'name'                 => $p->name,
                'code'                 => $p->code_name,
                'price'                => $p->price,
                'stock'                => $p->availableStock(),
                'requiresPrescription' => (bool) $p->requires_prescription,
            ])
            ->values();

        return response()->json(['products' => $products]);
    }

    /**
     * Record an already-finished sale (e.g. a walk-in customer) as a
     * COMPLETED order. The customer is matched by email, or created in the
     * users table when new, and each item's quantity is deducted from an
     * active, unexpired stock batch — all in one transaction, so a stock
     * problem leaves no half-created order or customer behind.
     *
     * Multipart request: app-paid orders need a proof_of_payment image, and
     * every prescription-only product needs its own prescription_file image.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer.first_name'      => ['required', 'string', 'max:255'],
            'customer.last_name'       => ['required', 'string', 'max:255'],
            'customer.email'           => ['nullable', 'email', 'max:255'],
            'customer.contact_number'  => ['nullable', 'string', 'max:20'],
            'customer.address'         => ['nullable', 'string', 'max:500'],
            'customer.password'        => ['nullable', 'string', 'min:6', 'max:255'],
            'customer.profile_picture' => ['nullable', 'image', 'max:5120'],
            'payment_method'           => ['required', 'in:cash,app'],
            'payment'                  => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'proof_of_payment'         => ['nullable', 'image', 'max:5120'],
            'order_date'               => ['nullable', 'date', 'before_or_equal:now'],
            'items'                    => ['required', 'array', 'min:1'],
            'items.*.product_id'       => ['required', 'integer', 'distinct', 'exists:products,id'],
            'items.*.quantity'         => ['required', 'integer', 'min:1', 'max:10000'],
            'items.*.prescription_file'=> ['nullable', 'image', 'max:5120'],
        ], [
            'customer.first_name.required' => 'Please enter the customer\'s first name.',
            'customer.last_name.required'  => 'Please enter the customer\'s last name.',
            'customer.email.email'         => 'Please enter a valid email address.',
            'customer.password.min'        => 'The password must be at least 6 characters.',
            'payment.numeric'              => 'Enter a valid payment amount.',
            'payment.min'                  => 'Enter a valid payment amount.',
            'items.required'               => 'Add at least one product.',
            'items.*.product_id.distinct'  => 'The same product is listed more than once.',
            '*.image'                      => 'Uploaded files must be images.',
            '*.max'                        => 'Uploaded images may not be larger than 5 MB.',
        ]);

        if ($data['payment_method'] === 'app' && ! $request->hasFile('proof_of_payment')) {
            throw ValidationException::withMessages(['proof_of_payment' => 'Please upload the proof of payment for this app payment.']);
        }

        // Cash sales record the money received (must cover the total, checked
        // once the total is known, inside the transaction). App sales were
        // paid online, so payment is recorded as the exact total.
        if ($data['payment_method'] === 'cash' && ! $request->filled('payment')) {
            throw ValidationException::withMessages(['payment' => 'Please enter the payment amount received.']);
        }

        $when = ! empty($data['order_date']) ? Carbon::parse($data['order_date']) : now();

        $order = DB::transaction(function () use ($request, $data, $when) {
            $products = Product::whereIn('id', collect($data['items'])->pluck('product_id'))
                ->get()
                ->keyBy('id');

            $lines    = [];
            $subtotal = 0.0;

            // 1) Validate everything (stock + required uploads) before touching any data.
            foreach (array_values($data['items']) as $i => $line) {
                $product = $products[$line['product_id']];
                $qty     = (int) $line['quantity'];

                if ($product->requires_prescription && ! $request->hasFile("items.$i.prescription_file")) {
                    throw ValidationException::withMessages([
                        'items' => "Please upload the prescription for \"{$product->name}\".",
                    ]);
                }

                // One batch must cover the whole line (order_items.stock_id is a single batch).
                $stock = Stock::where('product_id', $product->id)
                    ->where('is_active', true)
                    ->where('quantity', '>=', $qty)
                    ->where(fn ($q) => $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()))
                    ->orderByRaw('expiry_date is null')
                    ->orderBy('expiry_date')
                    ->lockForUpdate()
                    ->first();

                if (! $stock) {
                    throw ValidationException::withMessages([
                        'items' => "Not enough available stock for \"{$product->name}\" (requested {$qty}).",
                    ]);
                }

                $lineSubtotal = round($product->price * $qty, 2);
                $subtotal    += $lineSubtotal;

                $lines[] = compact('i', 'product', 'stock', 'qty', 'lineSubtotal');
            }

            // Cash must cover the total; app orders are paid in full.
            $payment = $data['payment_method'] === 'cash'
                ? round((float) $data['payment'], 2)
                : round($subtotal, 2);

            if ($payment < round($subtotal, 2)) {
                throw ValidationException::withMessages(['payment' => 'The payment is less than the order total.']);
            }

            // 2) Customer + uploads + order.
            $customer = $this->resolveCustomer($data['customer'], $request->file('customer.profile_picture'));

            $order = new Order([
                'user_id'          => $customer->id,
                'order_number'     => Order::generateOrderNumber(),
                'status'           => 'picked_up',
                'subtotal'         => round($subtotal, 2),
                'total'            => round($subtotal, 2),
                'payment'          => $payment,
                'payment_method'   => $data['payment_method'],
                'proof_of_payment' => $this->storePublic($request->file('proof_of_payment'), 'payment-proofs'),
                'confirmed_at'     => $when,
                'ready_at'         => $when,
                'picked_up_at'     => $when,
            ]);
            $order->created_at = $when;
            $order->save();

            foreach ($lines as $l) {
                OrderItem::create([
                    'order_id'          => $order->id,
                    'product_id'        => $l['product']->id,
                    'stock_id'          => $l['stock']->id,
                    'quantity'          => $l['qty'],
                    'unit_price'        => $l['product']->price,
                    'unit_old_price'    => $l['product']->old_price,
                    'subtotal'          => $l['lineSubtotal'],
                    'prescription_file' => $this->storePublic($request->file("items.{$l['i']}.prescription_file"), 'prescriptions'),
                ]);

                $l['stock']->decrement('quantity', $l['qty']);
            }

            return $order;
        });

        // The quoted order number is picked up by LogAdminActivity as the log label.
        return $this->ok("Order \"{$order->order_number}\" recorded as completed.", $order);
    }

    /**
     * Stores an upload on the public disk and returns the public-relative
     * path ("storage/…") so asset() can serve it — same convention as
     * proof_of_refund. Returns null when nothing was uploaded.
     */
    private function storePublic($file, string $dir): ?string
    {
        return $file ? 'storage/' . $file->store($dir, 'public') : null;
    }

    /**
     * Find the customer by email, or create a new users row for them.
     * Walk-ins without an email get a unique placeholder (users.email is
     * required + unique). An existing account is reused untouched — the
     * password / photo typed in the modal only apply to a NEW customer.
     */
    private function resolveCustomer(array $input, $profilePicture = null): User
    {
        $email = Str::lower(trim((string) ($input['email'] ?? '')));

        if ($email !== '') {
            $existing = User::withTrashed()->where('email', $email)->first();

            if ($existing) {
                return $existing;
            }
        }

        return User::create([
            'first_name'      => trim($input['first_name']),
            'last_name'       => trim($input['last_name']),
            'email'           => $email !== '' ? $email : 'walkin-' . Str::lower(Str::random(12)) . '@walkin.local',
            'contact_number'  => ($input['contact_number'] ?? null) ?: null,
            'address'         => ($input['address'] ?? null) ?: null,
            'profile_picture' => $this->storePublic($profilePicture, 'profile-pictures'),
            'password'        => ($input['password'] ?? null) ?: 'admin123', // hashed by the User cast
            'is_active'       => true,
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

        // App-paid orders have no pickup timer, so only cash orders expire.
        $candidateIds = Order::where('status', 'ready')
            ->where('payment_method', '!=', 'app')
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
                    || ! $order->hasPickupTimer()
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

    /** Confirmed → Ready / For Pick Up ("Reserve" button). Starts the 5-hour clock (cash orders only). */
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

    /**
     * Ready → Picked up / Completed (receipt "Submit").
     *
     * Cash orders must send the `payment` the customer handed over, and it
     * must cover the total. App-paid orders were already paid online, so
     * their payment is recorded as the exact total (no change).
     */
    public function complete(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'payment' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
        ], [
            'payment.numeric' => 'Enter a valid payment amount.',
            'payment.min'     => 'Enter a valid payment amount.',
            'payment.max'     => 'The payment amount is too large.',
        ]);

        // Sweep first: an order past its window must not be completable.
        $this->autoExpireReadyOrders();
        $order->refresh();

        if ($order->status === 'cancelled' && $order->cancellation_reason === self::EXPIRED_REASON) {
            return $this->fail('This order has expired and was cancelled.');
        }

        if ($order->status !== 'ready') {
            return $this->fail('Only orders that are ready for pick up can be completed.');
        }

        if ($order->isOnlinePayment()) {
            $payment = (float) $order->total;
        } else {
            if (! $request->filled('payment')) {
                return $this->fail('Enter the payment amount received from the customer.');
            }

            $payment = round((float) $request->input('payment'), 2);

            if ($payment < (float) $order->total) {
                return $this->fail('The payment is less than the order total.');
            }
        }

        $order->markPickedUp($payment);

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
        // No due date for app-paid orders: they have no pickup timer.
        $dueAt = $order->status === 'ready' && $order->ready_at && $order->hasPickupTimer()
            ? $order->ready_at->copy()->addHours(self::PICKUP_WINDOW_HOURS)
            : null;

        return [
            'dbId'         => $order->id,
            'id'           => $order->order_number,
            'customer'     => $order->user?->full_name ?? 'Deleted user',
            'date'         => $order->created_at->format('M j · g:i A'),
            'payment'      => $order->isOnlinePayment() ? 'online' : 'cash',
            'status'       => self::UI_STATUS[$order->status] ?? $order->status,
            'total'        => $order->total,
            'paymentAmount'=> $order->payment,
            'change'       => $order->change(),
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