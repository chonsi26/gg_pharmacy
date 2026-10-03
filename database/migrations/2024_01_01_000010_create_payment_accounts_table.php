<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('payment_app');               // e.g. "GCash", "Maya" (PayMaya)
            $table->string('account_name');              // name registered on the account
            $table->string('account_number');            // mobile number / account number
            $table->string('image')->nullable();         // screenshot of the payment account (e.g. QR / profile)
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('payment_accounts'); }
};
