<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * The full cart page — every item the authenticated user has added,
     * laid out Shopee/TikTok-style: a checkbox per row, a quantity stepper,
     * a per-item subtotal, and a sticky bottom bar totalling whatever is
     * currently checked.
     */
    public function index(): View
    {
        $settings = Setting::allAsArray();
        $categories = Category::active()->orderBy('sort_order')->get();

        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['product.brand'])
            ->latest()
            ->get()
            // A product can be deleted outright after being added to a cart;
            // guard the view against rendering a null relation.
            ->filter(fn (Cart $item) => $item->product !== null)
            ->values();

        $cartTotal = $cartItems->sum(fn (Cart $item) => $item->subtotal());

        return view('cart', compact('settings', 'categories', 'cartItems', 'cartTotal'));
    }

    /**
     * Add a product to the authenticated user's cart, or bump its quantity
     * if it's already in there.
     *
     * Guests are NOT redirected to a login page — this endpoint is hit via
     * fetch() from cart.js, so a guest instead gets a 401 with
     * {status: 'guest', product_id, quantity} echoed back. The front-end
     * uses that payload to remember what was clicked, opens the login
     * modal, and re-submits the same add-to-cart request automatically
     * once the user logs in or registers.
     */
    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $quantity = $validated['quantity'] ?? 1;

        if (! Auth::check()) {
            return response()->json([
                'status'     => 'guest',
                'message'    => 'Please log in to add items to your cart.',
                'product_id' => $validated['product_id'],
                'quantity'   => $quantity,
            ], 401);
        }

        $product = Product::active()->find($validated['product_id']);

        if (! $product) {
            return response()->json([
                'status'  => 'error',
                'message' => 'This product is no longer available.',
            ], 404);
        }

        $cart = Cart::firstOrNew([
            'user_id'    => Auth::id(),
            'product_id' => $product->id,
        ]);

        // Bump the existing quantity instead of overwriting it, so adding the
        // same item twice accumulates rather than resets.
        $cart->quantity = $cart->exists ? $cart->quantity + $quantity : $quantity;
        $cart->quantity = min($cart->quantity, 99);
        $cart->save();

        return response()->json([
            'status'        => 'added',
            'message'       => $product->name . ' added to cart',
            'product_name'  => $product->name,
            'product_id'    => $product->id,
            'cart_count'    => $this->cartCount(),
            'cart_quantity' => $cart->quantity,
        ]);
    }

    /**
     * Number of distinct products currently in the authenticated user's
     * cart. Used by cart.js to refresh the little badge in the header on
     * every page load. Guests always see 0.
     */
    public function count(): JsonResponse
    {
        return response()->json([
            'cart_count' => $this->cartCount(),
        ]);
    }

    /**
     * Change the quantity of a single cart row. Used by the +/- stepper and
     * the manual quantity input on the cart page.
     */
    public function update(Request $request, Cart $cart): JsonResponse
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart->quantity = $validated['quantity'];
        $cart->save();

        return response()->json([
            'status'     => 'updated',
            'cart_id'    => $cart->id,
            'quantity'   => $cart->quantity,
            'subtotal'   => $cart->subtotal(),
            'cart_count' => $this->cartCount(),
        ]);
    }

    /**
     * Remove a single row from the cart (the per-item trash icon).
     */
    public function destroy(Cart $cart): JsonResponse
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        return response()->json([
            'status'     => 'removed',
            'cart_count' => $this->cartCount(),
        ]);
    }

    /**
     * Remove several rows at once (the "Delete selected" action in the
     * cart page's sticky bottom bar). Only ever touches rows the
     * authenticated user actually owns, regardless of which ids are posted.
     */
    public function destroySelected(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids'   => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ]);

        Cart::where('user_id', Auth::id())
            ->whereIn('id', $validated['ids'])
            ->delete();

        return response()->json([
            'status'     => 'removed',
            'cart_count' => $this->cartCount(),
        ]);
    }

    private function cartCount(): int
    {
        if (! Auth::check()) {
            return 0;
        }

        return Cart::where('user_id', Auth::id())->count();
    }
}