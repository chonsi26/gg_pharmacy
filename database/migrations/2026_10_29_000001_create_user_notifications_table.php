<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Customer-facing notifications (the bell in home.blade.php).
        // NOTE: the existing `notifications` table belongs to the admin bell,
        // so this one is deliberately separate.
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Optional links back to what the notification is about
            $table->foreignId('order_id')
                  ->nullable()
                  ->constrained('orders')
                  ->cascadeOnDelete();
            $table->foreignId('product_id')
                  ->nullable()
                  ->constrained('products')
                  ->nullOnDelete();

            // order_placed | order_confirmed | order_ready | order_picked_up |
            // order_cancelled | refund_sent | proof_required | prescription_required |
            // order_expiring | wishlist_sale | wishlist_low_stock | wishlist_restock |
            // welcome | promo
            $table->string('type', 40);

            $table->string('title');            // bold part, e.g. "Order ORD-20260101-00001"
            $table->text('message');            // plain text, escaped on output
            $table->string('url')->nullable();  // where the notification takes the customer

            // ── Priority / "needs your attention" ─────────────────────────
            // is_priority = true  → action is required from the customer.
            // resolved_at = null  → the action is still outstanding, so the
            //                       notification stays PINNED at the top of the
            //                       list (even after being read).
            // resolved_at set     → the customer handled it (or the order ended);
            //                       it drops down to a normal notification.
            $table->boolean('is_priority')->default(false);
            $table->timestamp('resolved_at')->nullable();

            // Deadline for priority items (payment window, pickup window, …)
            $table->timestamp('expires_at')->nullable();

            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            // When the underlying event happened (used for sorting / "2 hours ago")
            $table->timestamp('event_at')->useCurrent();

            // Stops the same event from being notified twice,
            // e.g. "order:15:status:confirmed" or "order:15:proof_required"
            $table->string('dedupe_key', 150);

            $table->timestamps();

            $table->unique(['user_id', 'dedupe_key']);
            $table->index(['user_id', 'is_read']);
            $table->index(['user_id', 'is_priority', 'resolved_at']);
            $table->index(['user_id', 'event_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
