<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ── Orders ─────────────────────────────────────────────────────────
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Human-readable reference shown to the customer (e.g. ORD-20240101-00001)
            $table->string('order_number')->unique();

            // Reservation lifecycle
            // pending   → order placed, awaiting pharmacy confirmation
            // confirmed → pharmacy acknowledged the reservation
            // ready     → items are set aside and ready for pickup
            // picked_up → customer collected the order (terminal ✓)
            // cancelled → order was cancelled by customer or pharmacy (terminal ✗)
            $table->enum('status', [
                'pending',
                'confirmed',
                'ready',
                'picked_up',
                'cancelled',
            ])->default('pending');

            // Pricing snapshot (avoids re-calculating from items on every query)
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('total',    10, 2)->default(0);

            // How the customer intends to pay:
            // cash       → pay at the pharmacy upon pickup
            // app → pay via an online/e-wallet app (proof_of_payment may be uploaded)
            $table->enum('payment_method', ['cash', 'app'])->default('cash');

            // Optional: uploaded screenshot/receipt when paying via app.
            // Stays null for cash orders, and is optional even for app.
            $table->string('proof_of_payment')->nullable();

            // Optional: uploaded screenshot/receipt of the refund the pharmacy sent
            // back to the customer. Only relevant when an order paid via app is
            // cancelled; stays null otherwise.
            $table->string('proof_of_refund')->nullable();

            // Optional: customer note / prescription note left at reservation time
            $table->text('note')->nullable();

            // Optional: reason recorded when pharmacy or customer cancels
            $table->text('cancellation_reason')->nullable();

            // Timestamps for each status transition (nullable = not yet reached)
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('user_id');
        });

        // ── Order Items ────────────────────────────────────────────────────
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->cascadeOnDelete();

            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();

            // Since a product only ever has one ongoing active stock batch,
            // there's no need for a deduction ledger — just remember which
            // batch this line item's quantity came from, so cancelling the
            // order can add it straight back.
            $table->foreignId('stock_id')
                  ->nullable()
                  ->constrained('stocks')
                  ->nullOnDelete();

            $table->unsignedInteger('quantity')->default(1);

            // Price snapshot at the time of reservation
            // (product price may change later, so we store it here)
            $table->decimal('unit_price',     10, 2);
            $table->decimal('unit_old_price', 10, 2)->nullable();
            $table->decimal('subtotal',       10, 2);   // unit_price × quantity

            // Uploaded prescription file for this specific line item, when its
            // product requires a prescription (see products.requires_prescription)
            $table->string('prescription_file')->nullable();

            $table->timestamps();

            $table->unique(['order_id', 'product_id']);
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};