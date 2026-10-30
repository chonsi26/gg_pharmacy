<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Notification bell for the STAFF portal.
 *
 * Same shape as the admin `notifications` table (base table + alert columns
 * merged into one migration) but kept in its own table, so staff and admin
 * read/unread states never interfere with each other.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('staff_notifications', function (Blueprint $table) {
            $table->id();

            // Stable identity of the thing the notification is about, e.g.
            //   low_stock:product:12 | expiring_stock:stock:7 | order_open:55
            // Lets the controller update an existing row instead of duplicating it.
            $table->string('ref_key')->nullable()->unique();

            $table->string('type');
            $table->string('title')->nullable();
            $table->text('message');
            $table->string('link')->nullable();
            $table->boolean('is_read')->default(false);

            // 0 = informational
            // 1 = medium  (low stock, confirmed/ready orders)
            // 2 = high    (expiring stock, new order awaiting confirmation)
            // 3 = critical(out of stock, expired stock, refund still owed)
            $table->unsignedTinyInteger('priority')->default(0);

            // Set automatically once the underlying problem is handled
            // (restocked, order finished, refund sent, …).
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

            $table->index(['resolved_at', 'priority', 'created_at'], 'staff_notifications_feed_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_notifications');
    }
};
