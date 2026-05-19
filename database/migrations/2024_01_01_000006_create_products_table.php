<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->decimal('old_price', 10, 2)->nullable();
            $table->string('image');
            $table->string('badge')->nullable();                   // e.g. "-10%", "MOST SOLD"
            // null | "sale-badge" | "most-sold"
            $table->string('badge_type')->nullable();

            // ── Product Details ────────────────────────────────────────────
            $table->text('description')->nullable();               // full product description
            $table->string('origin')->nullable();                  // country / place of origin
            $table->text('product_usage')->nullable();             // how to use / directions
            $table->text('ingredients')->nullable();               // active & inactive ingredients
            $table->text('warnings')->nullable();                  // safety warnings (optional)

            // ── Dimensions (optional) ──────────────────────────────────────
            $table->decimal('width', 8, 2)->nullable();            // in cm
            $table->decimal('height', 8, 2)->nullable();           // in cm
            $table->decimal('depth', 8, 2)->nullable();            // in cm

            // ── Relationships ──────────────────────────────────────────────
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('categories')
                  ->nullOnDelete();
            $table->foreignId('brand_id')
                  ->nullable()
                  ->constrained('brands')
                  ->nullOnDelete();
            $table->foreignId('section_id')
                  ->constrained('sections')
                  ->cascadeOnDelete();
            // ──────────────────────────────────────────────────────────────

            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('products'); }
};