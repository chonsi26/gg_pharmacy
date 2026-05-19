<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ticker_image')->nullable();   // small logo for the ticker row
            $table->string('featured_image')->nullable(); // banner image for the featured grid
            // CSS class that controls the background of the featured card
            // e.g. "orange" | "darkred" | "darkgreen" | "white" | "salmon"
            $table->string('featured_color')->nullable();
            $table->boolean('show_in_ticker')->default(true);
            $table->boolean('show_in_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('brands'); }
};
