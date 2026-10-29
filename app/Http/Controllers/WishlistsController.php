<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistsController extends Controller
{
    /**
     * Show the logged-in customer's wishlist page.
     */
    public function index(Request $request): View
    {
        $settings   = Setting::allAsArray();
        $categories = Category::active()->orderBy('sort_order')->get();

        // Newest saved first. Products an admin has since deactivated are
        // hidden (the wishlist row is kept, so they reappear if reactivated).
        $wishlists = Wishlist::where('user_id', $request->user()->id)
            ->whereHas('product', fn ($q) => $q->active())
            ->with(['product.brand', 'product.category'])
            ->latest()
            ->get();

        return view('wishlists', compact('settings', 'categories', 'wishlists'));
    }

    /**
     * Add the product to the customer's wishlist, or remove it if it is
     * already there (used by the heart button on the product page).
     * Always responds with JSON.
     */
    public function toggle(Request $request, Product $product): JsonResponse
    {
        $userId = $request->user()->id;

        $existing = Wishlist::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $wishlisted = false;
            $message    = 'Removed from your wishlist.';
        } else {
            Wishlist::firstOrCreate([
                'user_id'    => $userId,
                'product_id' => $product->id,
            ]);
            $wishlisted = true;
            $message    = 'Added to your wishlist!';
        }

        return response()->json([
            'status'     => 'success',
            'wishlisted' => $wishlisted,
            'message'    => $message,
            'count'      => Wishlist::where('user_id', $userId)->count(),
        ]);
    }

    /**
     * Remove a product from the wishlist (used by the Remove button on the
     * wishlist page). JSON for AJAX, redirect for a plain form submit.
     */
    public function destroy(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        $userId = $request->user()->id;

        Wishlist::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Removed from your wishlist.',
                'count'   => Wishlist::where('user_id', $userId)->count(),
            ]);
        }

        return redirect()
            ->route('wishlists.index')
            ->with('success', 'Removed from your wishlist.');
    }
}
