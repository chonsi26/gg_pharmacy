<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();

            $table->string('supplier')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('unit_cost', 10, 2);
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('stocks'); }
};