<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Money handed over by the customer when the order is picked up.
            // Change is not stored; it is derived as (payment - total).
            // Null until the order is completed (and for older orders).
            $table->decimal('payment', 10, 2)->nullable()->after('total');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment');
        });
    }
};
