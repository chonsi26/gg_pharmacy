<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('promo_banners', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('alt')->nullable();
            $table->string('type')->default('red');  // red | beige
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('promo_banners'); }
};
