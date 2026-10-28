<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('card_images', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique();  // card_image_1 | card_image_2
            $table->unsignedTinyInteger('sort_order')->unique(); // 1 or 2 — drives section_key
            $table->string('image')->nullable();
            $table->string('alt')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('card_images'); }
};
