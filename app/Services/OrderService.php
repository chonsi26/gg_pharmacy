<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Carbon;
use RuntimeException;

class OrderService
{
    /**
     * An order sitting in "ready" status (i.e. set aside and waiting for
     * pickup) longer than this is treated as an expired reservation and
     * auto-cancelled.
     */
    public const PICKUP_EXPIRY_HOURS = 5;

    /**
     * Deduct `$quantity` units from `$product`'s single ongoing active
     * stock batch. The system only ever allows one such batch per product,
     * so there's nothing to split across — just find it, lock it, and take
     * from it. Records which batch was used on `$item->stock_id` so it can
     * be restored precisely if the order is cancelled.
     */
    public function deductStock(Product $product, int $quantity, OrderItem $item): void
    {
        $stock = $product->stocks()
            ->where('is_active', true)
            ->where('quantity', '>=', $quantity)
            ->where(function ($q) {
                $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now());
            })
            ->lockForUpdate()
            ->first();

        if (! $stock) {
            throw new RuntimeException("Not enough stock available for \"{$product->name}\".");
        }

        $stock->decrement('quantity', $quantity);

        $item->update(['stock_id' => $stock->id]);
    }

    /**
     * Add every order item's quantity back to the exact stock batch it was
     * deducted from. If that batch no longer exists (e.g. deleted since),
     * `stock_id` will have been nulled out by the FK's nullOnDelete — in
     * that case there's simply nothing to restore it to.
     */
    public function releaseStock(Order $order): void
    {
        $order->loadMissing('items');

        foreach ($order->items as $item) {
            if ($item->stock_id) {
                Stock::whereKey($item->stock_id)->increment('quantity', $item->quantity);
            }
        }
    }

    /**
     * Find this user's (or, with no argument, everyone's) "ready" orders
     * whose ready_at has passed the pickup window, restock them, and mark
     * them cancelled with reason "Order expired". Called opportunistically
     * whenever the orders page is loaded so the status is always fresh,
     * without needing a queue worker or scheduled task running.
     */
    public function autoExpireReadyOrders(?int $userId = null): void
    {
        $cutoff = Carbon::now()->subHours(self::PICKUP_EXPIRY_HOURS);

        $query = Order::where('status', 'ready')
            ->whereNotNull('ready_at')
            ->where('ready_at', '<=', $cutoff);

        if ($userId !== null) {
            $query->where('user_id', $userId);
        }

        $query->with('items')->get()->each(function (Order $order) {
            $this->releaseStock($order);
            $order->markCancelled('Order expired');
        });
    }
}
