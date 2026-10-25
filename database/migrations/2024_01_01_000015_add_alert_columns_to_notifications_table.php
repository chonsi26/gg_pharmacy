<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Stable identity of the thing the notification is about, e.g.
            //   low_stock:product:12 | expiring_stock:stock:7 | order_open:55
            // Lets the controller update an existing row instead of duplicating it.
            $table->string('ref_key')->nullable()->unique()->after('id');

            $table->string('title')->nullable()->after('type');
            $table->string('link')->nullable()->after('message');

            // 0 = informational
            // 1 = medium  (low stock, confirmed/ready orders)
            // 2 = high    (expiring stock, new order awaiting confirmation)
            // 3 = critical(out of stock, expired stock, refund still owed)
            $table->unsignedTinyInteger('priority')->default(0)->after('is_read');

            // Set automatically once the underlying problem is handled
            // (restocked, order finished, refund sent, …).
            $table->timestamp('resolved_at')->nullable()->after('priority');

            $table->index(['resolved_at', 'priority', 'created_at'], 'notifications_feed_index');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_feed_index');
            $table->dropUnique(['ref_key']);
            $table->dropColumn(['ref_key', 'title', 'link', 'priority', 'resolved_at']);
        });
    }
};
