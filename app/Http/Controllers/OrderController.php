<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentAccount;
use App\Models\Product;
use App\Models\Setting;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    /**
     * "Buy Now" on the product page. Doesn't touch the cart or the database
     * at all yet — it just stashes what the customer wants to order in the
     * session and sends them to the review page (myorder.blade.php), where
     * nothing is final until they actually click "Place Order".
     */
    public function reviewBuyNow(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::active()->find($validated['product_id']);

        if (! $product) {
            return redirect()->route('home')->with('error', 'This product is no longer available.');
        }

        session(['pending_order' => [
            'source' => 'buy_now',
            'items'  => [[
                'product_id' => $product->id,
                'quantity'   => $validated['quantity'] ?? 1,
                'cart_id'    => null,
            ]],
        ]]);

        return redirect()->route('order.myorders');
    }

    /**
     * "Checkout" on the cart page for whichever rows the customer checked.
     * Same idea as reviewBuyNow(): just stages the review, nothing is
     * written to orders/order_items until "Place Order" is clicked.
     */
    public function reviewCheckout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cart_ids'   => ['required', 'array', 'min:1'],
            'cart_ids.*' => ['integer'],
        ]);

        $cartRows = Cart::where('user_id', Auth::id())
            ->whereIn('id', $validated['cart_ids'])
            ->with('product')
            ->get()
            ->filter(fn (Cart $c) => $c->product !== null && $c->product->is_active)
            ->values();

        if ($cartRows->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Select at least one available item to check out.');
        }

        session(['pending_order' => [
            'source' => 'cart',
            'items'  => $cartRows->map(fn (Cart $c) => [
                'product_id' => $c->product_id,
                'quantity'   => $c->quantity,
                'cart_id'    => $c->id,
            ])->all(),
        ]]);

        return redirect()->route('order.myorders');
    }

    /**
     * My Orders page: an optional "review before you place it" panel (built
     * from the session, if reviewBuyNow/reviewCheckout just ran) sitting
     * above the customer's full order history, each with a cancel action
     * and a prescription upload where one is still needed.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Opportunistically sweep expired pickups every time this page loads
        // — see OrderService::autoExpireReadyOrders() for the 5-hour rule.
        $this->orderService->autoExpireReadyOrders($user->id);

        $orders = Order::where('user_id', $user->id)
            ->with(['items.product'])
            ->latest()
            ->get();

        // The seller's payment accounts (GCash, Maya, ...) — shown in the
        // "Payment Accounts" modal on confirmed app-payment orders.
        $paymentAccounts = PaymentAccount::active()->ordered()->get();

        $settings = Setting::allAsArray();

        // Categories drive the nav bar, which myorder.blade.php now shares
        // with home.blade.php.
        $categories = Category::active()->orderBy('sort_order')->get();

        return view('myorder', [
            'settings'   => $settings,
            'categories' => $categories,
            'orders'     => $orders,
            'paymentAccounts' => $paymentAccounts,
            'review'     => $this->buildReviewFromSession(),
        ]);
    }

    /**
     * Actually creates the order: re-validates everything against the
     * database (never trusts the session for price or availability),
     * deducts stock, snapshots line items, and — for a cart checkout —
     * removes the ordered rows from the cart.
     */
    public function store(Request $request): RedirectResponse
    {
        $pending = session('pending_order');

        if (! $pending || empty($pending['items'])) {
            return redirect()->route('order.myorders')
                ->with('error', 'Your order session has expired. Please try again.');
        }

        $user = Auth::user();

        // Payment method is picked in the review panel. Proof of payment is
        // NOT collected here — it's uploaded later, once the order is confirmed.
        $validatedPayment = $request->validate([
            'payment_method' => ['required', 'in:cash,app'],
        ], [
            'payment_method.required' => 'Please choose a payment method.',
            'payment_method.in'       => 'Please choose either Cash or App as your payment method.',
        ]);

        // Rx items must have their prescription attached to *this* request —
        // the review panel now collects it up front, so there's no more
        // "place the order, then upload it after" path for these products.
        $rxProductIds = Product::whereIn('id', collect($pending['items'])->pluck('product_id'))
            ->where('requires_prescription', true)
            ->pluck('id');

        if ($rxProductIds->isNotEmpty()) {
            $rules = [];
            $messages = [];

            foreach ($rxProductIds as $productId) {
                $rules["prescriptions.{$productId}"] = ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'];
                $messages["prescriptions.{$productId}.required"] = 'Please upload a prescription for this item before placing your order.';
                $messages["prescriptions.{$productId}.mimes"] = 'Prescriptions must be a JPG, PNG, or PDF file.';
                $messages["prescriptions.{$productId}.max"] = 'Each prescription file must not exceed 5 MB.';
            }

            $request->validate($rules, $messages);
        }

        try {
            $order = DB::transaction(function () use ($pending, $user, $request, $validatedPayment) {
                $subtotal = 0;
                $lines = [];

                foreach ($pending['items'] as $row) {
                    /** @var Product|null $product */
                    $product = Product::active()->lockForUpdate()->find($row['product_id']);

                    if (! $product) {
                        throw new RuntimeException('One of the items in your order is no longer available.');
                    }

                    $quantity = max(1, min(99, (int) $row['quantity']));

                    if ($product->availableStock() < $quantity) {
                        throw new RuntimeException(
                            "Not enough stock for \"{$product->name}\". Only {$product->availableStock()} left."
                        );
                    }

                    if ($product->requires_prescription && ! $request->hasFile("prescriptions.{$product->id}")) {
                        // Shouldn't happen given the validation above, but the
                        // pending session could in theory have changed between
                        // the GET review and this POST — guard anyway.
                        throw new RuntimeException("A prescription is required for \"{$product->name}\".");
                    }

                    $lineSubtotal = round($product->price * $quantity, 2);
                    $subtotal += $lineSubtotal;

                    $lines[] = [
                        'product'      => $product,
                        'quantity'     => $quantity,
                        'subtotal'     => $lineSubtotal,
                        'cart_id'      => $row['cart_id'] ?? null,
                    ];
                }

                $order = Order::create([
                    'user_id'      => $user->id,
                    'order_number' => Order::generateOrderNumber(),
                    'status'         => 'pending',
                    'payment_method' => $validatedPayment['payment_method'],
                    'subtotal'     => $subtotal,
                    // No delivery fee / tax modeled yet, so total == subtotal for now.
                    'total'        => $subtotal,
                ]);

                foreach ($lines as $line) {
                    $product = $line['product'];

                    $prescriptionPath = null;

                    if ($product->requires_prescription) {
                        $path = $request->file("prescriptions.{$product->id}")->store('prescriptions', 'public');
                        $prescriptionPath = 'storage/' . $path;
                    }

                    $item = OrderItem::create([
                        'order_id'          => $order->id,
                        'product_id'        => $product->id,
                        'quantity'          => $line['quantity'],
                        'unit_price'        => $product->price,
                        'unit_old_price'    => $product->old_price,
                        'subtotal'          => $line['subtotal'],
                        'prescription_file' => $prescriptionPath,
                    ]);

                    $this->orderService->deductStock($product, $line['quantity'], $item);

                    if ($line['cart_id']) {
                        Cart::where('id', $line['cart_id'])->where('user_id', $user->id)->delete();
                    }
                }

                return $order;
            });
        } catch (RuntimeException $e) {
            return redirect()->route('order.myorders')->with('error', $e->getMessage());
        }

        session()->forget('pending_order');

        return redirect()->route('order.myorders')
            ->with('success', "Order {$order->order_number} placed! We'll notify you once the pharmacy confirms it.")
            ->with('new_order_id', $order->id);
    }

    /**
     * Backs out of a staged review (e.g. the customer clicked Buy Now by
     * mistake, or changed their mind before placing the order). Nothing was
     * ever written to the database for it, so this just clears the session.
     */
    public function discardReview(): RedirectResponse
    {
        session()->forget('pending_order');

        return redirect()->route('order.myorders');
    }

    /**
     * Customer-initiated cancellation. Restocks whatever this order had
     * deducted, then marks it cancelled. Terminal orders (already picked up
     * or already cancelled) can't be touched.
     */
    public function cancel(Order $order): RedirectResponse
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (! $order->isCancellable()) {
            return redirect()->route('order.myorders')->with('error', 'This order can no longer be cancelled.');
        }

        DB::transaction(function () use ($order) {
            $this->orderService->releaseStock($order);
            $order->markCancelled('Cancelled by customer');
        });

        return redirect()->route('order.myorders')->with('success', "Order {$order->order_number} was cancelled.");
    }

    /**
     * Upload a prescription image/PDF for a single line item whose product
     * requires one. Kept per-item (not per-order) since one order can mix
     * Rx and OTC products.
     */
    public function uploadPrescription(Request $request, OrderItem $orderItem): RedirectResponse
    {
        if ($orderItem->order->user_id !== Auth::id()) {
            abort(403);
        }

        if (! $orderItem->requiresPrescription()) {
            abort(422, 'This item does not require a prescription.');
        }

        $validated = $request->validate([
            'prescription' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'prescription.required' => 'Please choose a file to upload.',
            'prescription.mimes'    => 'The prescription must be a JPG, PNG, or PDF file.',
            'prescription.max'      => 'The prescription file must not exceed 5 MB.',
        ]);

        $path = $request->file('prescription')->store('prescriptions', 'public');

        $orderItem->update(['prescription_file' => 'storage/' . $path]);

        return redirect()->route('order.myorders')->with('success', 'Prescription uploaded.');
    }

    /**
     * Upload (or replace) proof of payment for an "app" order. Only allowed
     * once the pharmacy has confirmed the order (status: confirmed).
     */
    public function uploadProofOfPayment(Request $request, Order $order): RedirectResponse
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (! $order->isOnlinePayment()) {
            return redirect()->route('order.myorders')
                ->with('error', 'Proof of payment is only needed for orders paid through an app.');
        }

        if (! $order->canUploadProofOfPayment()) {
            return redirect()->route('order.myorders')
                ->with('error', 'You can upload proof of payment only after the pharmacy has confirmed your order.');
        }

        $request->validateWithBag('proof_' . $order->id, [
            'proof_of_payment' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'proof_of_payment.required' => 'Please choose a file to upload.',
            'proof_of_payment.mimes'    => 'The proof of payment must be a JPG, PNG, or PDF file.',
            'proof_of_payment.max'      => 'The proof of payment must not exceed 5 MB.',
        ]);

        $path = $request->file('proof_of_payment')->store('proofs', 'public');

        // Replacing an earlier upload: clean up the old file.
        if ($order->proof_of_payment) {
            Storage::disk('public')->delete(Str::after($order->proof_of_payment, 'storage/'));
        }

        $order->update(['proof_of_payment' => 'storage/' . $path]);

        return redirect()->route('order.myorders')->with('success', "Proof of payment uploaded for order {$order->order_number}.");
    }

    /**
     * Track Order page (trackorder.blade.php): a Shopee-style progress view
     * with the pharmacy's map for pickup. The button that links here only
     * shows for "ready" orders on My Orders, but the page itself works for any
     * of the customer's live orders so the link never dead-ends. Cancelled
     * orders have nothing to track, so they go back to the list.
     */
    public function track(Order $order): View|RedirectResponse
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status === 'cancelled') {
            return redirect()->route('order.myorders')
                ->with('error', 'Cancelled orders can\'t be tracked.');
        }

        $order->load(['items.product']);

        // Ready orders are held for 5 hours (the same window
        // OrderService::autoExpireReadyOrders() enforces), so show the cut-off.
        $pickupDeadline = null;

        if ($order->status === 'ready') {
            $pickupDeadline = ($order->ready_at ?? $order->updated_at)->copy()->addHours(5);
        }

        return view('trackorder', [
            'order'          => $order,
            'pickupDeadline' => $pickupDeadline,
            'settings'       => Setting::allAsArray(),
            'categories'     => Category::active()->orderBy('sort_order')->get(),
        ]);
    }

    /**
     * Turns whatever's staged in session('pending_order') into fresh data
     * for the review panel — always re-reading the product from the
     * database (price, active status, current stock) rather than trusting
     * anything cached in the session.
     */
    private function buildReviewFromSession(): ?array
    {
        $pending = session('pending_order');

        if (! $pending || empty($pending['items'])) {
            return null;
        }

        $lines = collect($pending['items'])
            ->map(function (array $row) {
                $product = Product::active()->with(['category', 'brand'])->find($row['product_id']);

                if (! $product) {
                    return null;
                }

                $quantity = max(1, min(99, (int) $row['quantity']));

                return [
                    'product'         => $product,
                    'quantity'        => $quantity,
                    'subtotal'        => round($product->price * $quantity, 2),
                    'available_stock' => $product->availableStock(),
                ];
            })
            ->filter()
            ->values();

        if ($lines->isEmpty()) {
            session()->forget('pending_order');

            return null;
        }

        return [
            'source' => $pending['source'],
            'lines'  => $lines,
            'total'  => $lines->sum('subtotal'),
        ];
    }
}