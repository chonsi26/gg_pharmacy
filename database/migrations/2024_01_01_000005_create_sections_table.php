<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            // slug used in code: hot_deals | sale | promo_packs | guardian |
            //                    generics | featured | exclusively
            $table->string('key')->unique();
            $table->string('label');                     // display heading
            $table->string('description')->nullable();   // sub-heading
            $table->string('see_all_url')->nullable();
            // heading colour class — "red" | "text" (default dark)
            $table->string('heading_color')->default('red');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('sections'); }
};
